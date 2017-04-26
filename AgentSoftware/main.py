"""
This file collects the necessary data and sends over to the server

"""

__author__ = "Mazharul Onim"

import time
from threading import Thread
from wmic import *
from agent import *
from utils import *

start=time.time()

thread_result = {}   #dictionary is thread-safe

#thread for total available memory
thread1 = Thread(target=total_available_memory, args=(thread_result,'ram_available'))
thread1.start()

#thread for last_shutdown time
thread2 = Thread(target=threaded_last_shutdown, args=(thread_result,'last_shutdown'))
thread2.start()

#thread for cpu usage
thread3 = Thread(target=threaded_cpu_usage, args=(thread_result,'cpu_usage'))
thread3.start()

windows = CmdWmic("os", "Caption").run().get_result()
architecture = CmdWmic("os", "OSArchitecture").run().get_result()
num_cores = get_num_cores()
clock_speed = CmdWmic("cpu", "MaxClockSpeed").run().get_result()
ram_total = CmdWmic("computersystem", "TotalPhysicalMemory").run().get_gigabytes()
ram_free = CmdWmic("os", "FreePhysicalMemory").run().get_gigabytes()
ram_max_capacity = CmdWmic("memphysical", "MaxCapacity").run().get_gigabytes()
disk_total = get_total_disk_space()
disk_free = get_free_disk_space()
uptime = uptime()
ip = get_ip()
mac = get_mac()

num_process = CmdWmic("os", "NumberOfProcesses").run().get_result()
last_bootup = pretty_print_time(CmdWmic("os", "LastBootupTime").run().get_result())

thread1.join()
thread2.join()
thread3.join()
cpu_usage = thread_result['cpu_usage']
last_shutdown = pretty_print_time(thread_result['last_shutdown'])
ram_free = thread_result['ram_available']

print "IP address:", ip
print "MAC address:", mac
print "Windows Version:", windows
print "Architecture:", architecture
print "Number of CPU cores:", num_cores
print "Clock Speed:", clock_speed
print "CPU Usage:", cpu_usage
print "Total RAM:", ram_total
print "Available RAM:", ram_free
print "Maximum RAM capacity:", ram_max_capacity
print "Total Disk Space:", disk_total
print "Free Disk Space:", disk_free
print "Uptime:", uptime

print "Number of Processes:", num_process
print "Last bootup: ", last_bootup
print "Last shutdown: ", last_shutdown

end=time.time()
print end-start