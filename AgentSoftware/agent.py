"""
This file contains important functions that return various system
information using Python's WMI module

Author: Mazharul Onim
"""

import multiprocessing
import wmi
import time
from datetime import datetime
from threading import Thread
from multiprocessing.pool import ThreadPool
from wmic import CmdWmic

wmi_obj = wmi.WMI()

def get_num_cpu():
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
    last_bootup = CmdWmic("os", "LastBootupTime").run().get_value()
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
    cpu_usage = CmdWmic("cpu", "LoadPercentage").run().get_value()
    return cpu_usage

def threaded_cpu_usage(dict, key):
    cpu_usage = CmdWmic("cpu", "LoadPercentage").run().get_value()
    dict[key]=cpu_usage

def last_shutdown():
    
    wql = "SELECT * FROM Win32_NTLogEvent WHERE LogFile='System' AND EventCode='6006'"
    wql_run = wmi_obj.query(wql)
    return wql_run[0].TimeGenerated
    
def threaded_last_shutdown(dict, key):
    import pythoncom
    pythoncom.CoInitialize()
    w = wmi.WMI()
    try:
        wql = "SELECT * FROM Win32_NTLogEvent WHERE LogFile='System' AND EventCode='6006'"
        wql_run = w.query(wql)
        last_shutdown = wql_run[0].TimeGenerated
        dict[key] = pretty_print_time(last_shutdown)
    finally:
        pythoncom.CoUninitialize()
