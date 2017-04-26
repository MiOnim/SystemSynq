"""
This file contains important functions that return various system
information using Python's WMI module

Author: Mazharul Onim
"""

import multiprocessing
import os
import re
import wmi
import socket
from uuid import getnode
from datetime import datetime
from threading import Thread
from multiprocessing.pool import ThreadPool
from wmic import CmdWmic

wmi_obj = wmi.WMI()

def get_num_cores():
    return multiprocessing.cpu_count()

def get_total_disk_space():
    total = 0
    for d in wmi_obj.Win32_LogicalDisk(DriveType=3):
        total = total + int(d.Size)
    return "%.2f" %(total/1.073741824e9)

def get_free_disk_space():
    total = 0
    for d in wmi_obj.Win32_LogicalDisk(DriveType=3):
        total = total + int(d.FreeSpace)
    return "%.2f" %(total/1.073741824e9)

def list_all_process():
    processes = ""
    for p in wmi_obj.Win32_Process():
        processes += p.Name + ","
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

def pretty_print_time(value):
    value = value.split('.')[0]
    try:
        dt = datetime.strptime(value, "%Y%m%d%H%M%S")
    except ValueError:
        return value
    return dt.strftime("%Y/%m/%d %H:%M:%S")

def cpu_usage():
    cpu_usage = CmdWmic("cpu", "LoadPercentage").run().get_result()
    return cpu_usage

def threaded_cpu_usage(dict, key):
    cpu_usage = CmdWmic("cpu", "LoadPercentage").run().get_result()
    dict[key]=cpu_usage

def last_shutdown():
    last_shutdown = EventViewer('System',code='6006').run().get_time_generated(1)
    return ''.join(last_shutdown)
    
def threaded_last_shutdown(dict, key):
    import pythoncom
    pythoncom.CoInitialize()
    w = wmi.WMI()
    try:
        last_shutdown = EventViewer('System',code='6006',wmi=w).run().get_time_generated(1)
        dict[key] = pretty_print_time(''.join(last_shutdown))
    finally:
        pythoncom.CoUninitialize()

def total_available_memory(dict, key):
    system_info = os.popen("systeminfo").read()
    for line in system_info.split("\n"):
        if "Available Physical Memory" in line:
            x = line
    
    available_ram = "".join(re.findall(r'\d+,?\d+', x)).replace(',', '')
    in_gb = float(available_ram)/1024
    dict[key] = str(round(in_gb, 2)) + " GB"

def get_ip():
    hostname = socket.gethostname()
    return socket.gethostbyname(hostname)

def get_mac():
    mac = getnode()    #returns decimal value of the mac address
    return ':'.join(("%012X" % mac)[i:i+2] for i in range(0, 12, 2))



class EventViewer:
    
    def __init__(self, logfile, code="", type="", wmi=None):
        self.logfile = logfile
        self.eventcode = code
        self.type = type
        self.query_str = self.build_query_string()
        self.result = []
        self.wmi = wmi or wmi_obj
    
    def run(self):
        self.result = self.wmi.query(self.query_str)
        return self
    
    def build_query_string(self):
        query_str = "SELECT * FROM Win32_NTLogEvent WHERE LogFile='" + self.logfile + "'"
        if self.type:
            query_str += " AND Type='" + self.type + "'"
        if self.eventcode:
            query_str += " AND EventCode='" + self.eventcode + "'"
        return query_str
    
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
