"""
This file collects the necessary data and sends over to the server

"""

__author__ = "Mazharul Onim"

import time
from threading import Thread
from cmd import *
from agent import *
from utils import *
from db import *

start=time.time()

db = Db()

""" The startup commands below registers this computer in the database """

db.add_new_information(ID, hostname)
db.add_new_status(ID, hostname)
db.add_new_setting(ID)

""" End Startup Commands """

def main_run():
    
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
    ram_total = get_gigabytes(CmdWmic("computersystem", "TotalPhysicalMemory").run().get_result())
    ram_free = get_gigabytes(CmdWmic("os", "FreePhysicalMemory").run().get_result())
    disk_total = get_total_disk_space()
    disk_free = get_free_disk_space()
    #uptime = uptime()
    ip = get_ip()
    mac = get_mac()
    processes = list_all_process()
    users_logged = num_users_loggedon()

    num_process = CmdWmic("os", "NumberOfProcesses").run().get_result()
    last_bootup = pretty_print_time(CmdWmic("os", "LastBootupTime").run().get_result())

    thread1.join()
    thread2.join()
    thread3.join()
    cpu_usage = thread_result['cpu_usage']
    last_shutdown = pretty_print_time(thread_result['last_shutdown'])
    ram_free = thread_result['ram_available']
    
    db.update_status(hostname, ip, last_bootup, last_shutdown)
    db.update_information(hostname, windows, architecture, mac, num_cores, clock_speed, ram_total, disk_total)
    db.insert_into_running(hostname, ID, cpu_usage, ram_free, disk_free, processes, users_logged)
    
    print_args(name=hostname, ip=ip, mac=mac, windows=windows, architecture=architecture,
           num_cores=num_cores, clock_speed=clock_speed, cpu_usage=cpu_usage,
           ram_total=ram_total, ram_free=ram_free, processes=processes,
           disk_total=disk_total, disk_free=disk_free, uptime=uptime,
           num_process=num_process, last_bootup=last_bootup,
           last_shutdown=last_shutdown, users_logged=users_logged)



#while True:           
#    main_run()
#    time.sleep(5*60)

main_run()



#db = Db()
#db.update_status(hostname, ip, last_bootup, last_shutdown)
#db.update_information(hostname, windows, architecture, mac, num_cores, clock_speed, ram_total, disk_total)
#db.insert_into_running(hostname, cpu_usage, ram_free, disk_free, processes, users_logged)

end=time.time()
print end-start

#test = EventViewer('System',type='error').run().serialize()
#filename = write_to_file(test, ".\\events\\")
#remote_filename = "events-" + ID + ".txt"
#upload_file_to_server(filename, remote_filename)
