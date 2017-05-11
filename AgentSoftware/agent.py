"""
This file contains functions that return various system
information using Python's WMI module

It also includes EventViewer class, which encapsulates querying 
Windows Event Viewer, and getting results from it.

"""

__author__ = "Mazharul Onim"

import multiprocessing
import os
import re
import wmi
import socket
from datetime import datetime
from uuid import getnode
from cmd import *
from utils import *
from db import *
from limits import *

wmi_obj = wmi.WMI()
db = Db()

def get_num_cores():
    return multiprocessing.cpu_count()

def get_total_disk_space():
    total = 0
    for d in wmi_obj.Win32_LogicalDisk(DriveType=3):
        total = total + int(d.Size)
    return "%.2f GB" %(total/ONE_GB)

def get_free_disk_space():
    total = 0
    for d in wmi_obj.Win32_LogicalDisk(DriveType=3):
        total = total + int(d.FreeSpace)
    if total < REM_DISK_SPACE_LIMIT:   ## send alert
        alert_str = "Free Disk space below " + get_gigabytes(REM_DISK_SPACE_LIMIT)
        db.insert_into_alerts(ID, hostname, alert_str, "high")
    return "%.2f GB" %(total/ONE_GB)

def list_all_process():
    processes = ""
    for p in wmi_obj.Win32_Process():
        processes += p.Name + ","
    if len(processes.split(',')) > NUM_PROCESS_LIMIT:   ## send alert
        alert_str = "Number of processes higher than " + str(NUM_PROCESS_LIMIT)
        db.insert_into_alerts(ID, hostname, alert_str, "high")
    return processes[:-1]  #to remove the last comma

#May not be used. Makes more sense to process on the front-end
def uptime():
    last_bootup = CmdWmic("os", "LastBootupTime").run().get_result()
    last_bootup = last_bootup.split('-')[0]
    dt = datetime.strptime(last_bootup, "%Y%m%d%H%M%S.%f")
    now = datetime.now()
    time_diff = now - dt
    days = time_diff.days
    hours = time_diff.seconds / 3600
    minutes = (time_diff.seconds / 60)%60
    return "%s days, %s hours, %s minutes" % (days, hours, minutes)

def num_users_loggedon():
    list_explorer_owner = wmi_obj.Win32_Process(name='explorer.exe')
    return len(list_explorer_owner) #- 1   #subtract the admin user? 

def cpu_usage():
    cpu_usage = CmdWmic("cpu", "LoadPercentage").run().get_result()
    return cpu_usage

def threaded_cpu_usage(dict, key):
    cpu_usage = CmdWmic("cpu", "LoadPercentage").run().get_result()
    dict[key]=cpu_usage + " %"

def last_shutdown():
    last_shutdown = EventViewer('System',code='6006').run().get_time_generated(1)
    return ''.join(last_shutdown)
    
def threaded_last_shutdown(dict, key):
    import pythoncom
    pythoncom.CoInitialize()
    w = wmi.WMI()
    try:
        last_shutdown = EventViewer('System',code='6006',wmi=w).run().get_time_generated(1)
        dict[key] = ''.join(last_shutdown)
    finally:
        pythoncom.CoUninitialize()

def total_available_memory(dict, key):
    system_info = os.popen("systeminfo").read()
    for line in system_info.split("\n"):
        if "Available Physical Memory" in line:
            x = line
    #extract the value and convert to GB:
    available_ram = "".join(re.findall(r'\d+,?\d+', x)).replace(',', '')
    in_gb = float(available_ram)/1024
    if in_gb*ONE_GB < REM_FREE_RAM_LIMIT:   ## send alert
        alert_str = "Available RAM below " + get_megabytes(REM_FREE_RAM_LIMIT)
        db.insert_into_alerts(ID, hostname, alert_str, "high")
    dict[key] = str(round(in_gb, 2)) + " GB"

def get_hostname():
    return socket.gethostname()
    
def get_ip():
    return socket.gethostbyname(get_hostname())

def get_mac():
    mac = getnode()    #returns decimal value of the mac address
    return ':'.join(("%012X" % mac)[i:i+2] for i in range(0, 12, 2))

def generate_unique_id(hostname):
    comp_num = hostname[-3:]   #computer numbers at Hofstra are stored in the last 3 digit of the hostname
    return comp_num.strip('0') #remove leadin zeroes
    

""" The following values are unique to this machine
and will be used by other files """
hostname = get_hostname()
ID = generate_unique_id(hostname)
    
"""
The instance variables are:
    self.logfile   - the logfile to query from e.g. 'System', 'Application'
    self.eventcode - the 'EventID' field in Event Viewer
    self.type      - the 'level' field in Event Viewer e.g. 'Error', 'Information'
    self.query_str - the query string to be executed
    self.result    - a list of the results from the query
    self.wmi       - the WMI object. Default value is wmi_obj instantiated on top
    self.qtime     - the time of query; to display in the UI

"""
class EventViewer:
    
    def __init__(self, logfile, code="", type="", wmi=None):
        self.logfile = logfile
        self.eventcode = code
        self.type = type
        self.query_str = self.build_query_string()
        self.result = []
        self.wmi = wmi or wmi_obj
        self.qtime = ""
    
    def run(self):
        print "Querying Event logs: " + self.query_str
        try:
            self.result = self.wmi.query(self.query_str)
        except Exception:
            print "WMI Query failed ..."
        self.qtime = current_datetime()
        return self
    
    def build_query_string(self):
        query_str = "SELECT * FROM Win32_NTLogEvent WHERE LogFile='" + self.logfile + "'"
        if self.type:
            query_str += " AND Type='" + self.type + "'"
        if self.eventcode:
            query_str += " AND EventCode='" + self.eventcode + "'"
        return query_str
    
    def serialize(self):
        ret = self.qtime + "\n"
        #add the WHERE clause of the query string:
        ret += self.get_query_string().split("WHERE")[1].strip() + "\n"
        ret += "Log File,Level,Time Generated, Event ID, Message" + "\n"
        for item in self.result:
            ret += str(item.Logfile) + ","   #convert all of them to string because
            ret += str(item.Type) + ","      #some of the values are of None type
            ret += pretty_print_time(str(item.TimeGenerated)) + ","
            ret += str(item.EventCode) + ","
            """
            replace all the newlines in Message with a placeholder
            to prevent an event from spanning multiple lines
            """
            ret += str(item.Message).replace("\r\n", "{newline}") + ","
            ret += "\n"
        return ret
    
    def get_result(self):
        return self.result
    
    def get_message(self, n):
        return [self.result[x].Message for x in range(n)]
    
    def get_logfile(self):
        return self.logfile
    
    def get_type(self, n):
        return [self.result[x].Type for x in range(n)]
    
    def get_eventcode(self, n):
        return [self.result[x].EventCode for x in range(n)]
    
    def get_time_generated(self, n):
        return [self.result[x].TimeGenerated for x in range(n)]
    
    def get_query_string(self):
        return self.query_str
