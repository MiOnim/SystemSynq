import multiprocessing
import os
import wmi
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


num_cpu = multiprocessing.cpu_count()
clock_speed = Wmic("cpu", "MaxClockSpeed").run().get_value()
cpu_usage = Wmic("cpu", "LoadPercentage").run().get_value()
ram_total = Wmic("computersystem", "TotalPhysicalMemory").run().get_gigabytes()
ram_free = Wmic("os", "FreePhysicalMemory").run().get_gigabytes()
disk_total = get_total_disk_space()
disk_free = get_free_disk_space()

num_process = Wmic("os", "NumberOfProcesses").run().get_value()
last_bootup = Wmic("os", "LastBootupTime").run().get_pretty_time()
last_shutdown = time_last_shutdown()


print "Number of CPU:", num_cpu
print "Clock Speed:", clock_speed
print "CPU Usage:", cpu_usage
print "Total RAM:", ram_total
print "Free RAM:", ram_free
print "Total Disk Space:", disk_total
print "Free Disk Space:", disk_free

print "Number of Processes:", num_process
print "Last bootup: ", last_bootup
print "Last shutdown: ", last_shutdown