"""
This file contains some utility functions to be used on other files

"""

__author__ = "Mazharul Onim"

from datetime import datetime
import os

def current_datetime():
    now = datetime.now()
    return now.strftime("%Y/%m/%d %H:%M:%S")

def pretty_print_time(value):
    value = value.split('.')[0]
    try:
        dt = datetime.strptime(value, "%Y%m%d%H%M%S")
    except ValueError:
        return value
    return dt.strftime("%Y/%m/%d %H:%M:%S")

def print_args(**kwargs):
    for key in kwargs:
        print key + ':',
        print kwargs[key]
        
def write_to_file(string, filepath):
    if not os.path.exists(filepath):
        try:
            print "Creating directory", os.path.abspath(filepath)
            os.makedirs(filepath)
        except:
            raise OSError("Failed to create directory")
    filename = 'events_{}.txt'.format(datetime.now().strftime("%Y%m%d%H%M%S"))
    filepath = filepath + filename
    print "writing to file:", os.path.abspath(filepath)
    with open(filepath, 'w') as f:
        f.write(string)
        