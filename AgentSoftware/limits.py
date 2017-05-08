"""
This file lists limits for various system info e.g. free disk space, available RAM.
If the real value goes below this limit, it will send an alert to the System Administrator.

"""

__author__ = "Mazharul Onim"

from utils import *

REM_DISK_SPACE_LIMIT = 10*ONE_GB
REM_FREE_RAM_LIMIT = 800*ONE_MB
NUM_PROCESS_LIMIT = 100

def update_disk_space_limit(new_val):
    global REM_DISK_SPACE_LIMIT
    REM_DISK_SPACE_LIMIT = new_val
    
def update_free_ram_limit(new_val):
    global REM_FREE_RAM_LIMIT
    REM_FREE_RAM_LIMIT = new_val
    
def update_num_process_limit(new_val):
    global NUM_PROCESS_LIMIT
    NUM_PROCESS_LIMIT = new_val
    