"""
This file contains important functions that return various system information

Author: Mazharul Onim
"""

import multiprocessing
import wmi
import time
from Queue import Queue
from datetime import datetime
from threading import Thread
from wmic import Wmic

wmi_obj = wmi.WMI()


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

def time_last_shutdown():
    wql = "SELECT * FROM Win32_NTLogEvent WHERE LogFile='System' AND EventCode='6006'"
    wql_run = wmi_obj.query(wql)
    return wql_run[0].TimeGenerated

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

def enthread(target):
    q = Queue()
    def wrapper():
        q.put(target())
    t = Thread(target=wrapper)
    t.start()
    return q    

def threaded_cpu_usage():
    cpu_usage = Wmic("cpu", "LoadPercentage").run().get_value()
    print "CPU Usage:", cpu_usage

def threaded_time_last_shutdown():
    import pythoncom
    pythoncom.CoInitialize()
    w = wmi.WMI()
    try:
        wql = "SELECT * FROM Win32_NTLogEvent WHERE LogFile='System' AND EventCode='6006'"
        wql_run = w.query(wql)
        last_shutdown = wql_run[0].TimeGenerated
        print "Last shutdown: ", last_shutdown
    finally:
        pythoncom.CoUninitialize()
    

start = time.time()    

num_cpu = multiprocessing.cpu_count()
clock_speed = Wmic("cpu", "MaxClockSpeed").run().get_value()
#cpu = enthread(target=Wmic("cpu", "LoadPercentage").run().get_value())
#cpu_usage = cpu.get()
cpu_usage=""
thread = Thread(target=threaded_cpu_usage)
thread.start()
ram_total = Wmic("computersystem", "TotalPhysicalMemory").run().get_gigabytes()
ram_free = Wmic("os", "FreePhysicalMemory").run().get_gigabytes()
disk_total = get_total_disk_space()
disk_free = get_free_disk_space()

num_process = Wmic("os", "NumberOfProcesses").run().get_value()
last_bootup = pretty_print_time(Wmic("os", "LastBootupTime").run().get_value())
last_shutdown = pretty_print_time(time_last_shutdown())
#thread2 = Thread(target=threaded_time_last_shutdown)
#thread2.start()
#thread2.join()


print "Number of CPU:", num_cpu
print "Clock Speed:", clock_speed
#print "CPU Usage:", cpu_usage
print "Total RAM:", ram_total
print "Free RAM:", ram_free
print "Total Disk Space:", disk_total
print "Free Disk Space:", disk_free

print "Number of Processes:", num_process
print "Last bootup: ", last_bootup
print "Last shutdown: ", last_shutdown
#thread2.join()
end = time.time()
print (end-start)