"""
This file contains some utility functions to be used on other files

"""

__author__ = "Mazharul Onim"

from datetime import datetime, timedelta
import os
import requests
import json

ONE_KB = 1024.0
ONE_MB = 1.048576e6
ONE_GB = 1.073741824e9

ADMIN_URL = "http://10.22.12.139"


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

def subtract_hours(time, hours):
    """ Time has to be in this format: %Y%m%d%H%M%S """
    time_format = "%Y%m%d%H%M%S"
    time = time.split('.')[0]
    try:
        dt = datetime.strptime(time, time_format)
    except Exception:
        print "Error in subtract_hours(): Wrong time format"
        return time
    new_dt = dt - timedelta(minutes=60*hours)
    return new_dt.strftime(time_format)
    
def print_args(**kwargs):
    for key in kwargs:
        print key + ':',
        print kwargs[key]
        
def write_to_file(string, filepath, filename=""):
    if not os.path.exists(filepath):
        try:
            print "Creating directory", os.path.abspath(filepath)
            os.makedirs(filepath)
        except:
            raise OSError("Failed to create directory")
    if not filename:
        filename = 'events_{}.txt'.format(datetime.now().strftime("%Y%m%d%H%M%S"))
    filepath = filepath + filename
    print "Writing Windows Events to file:", os.path.abspath(filepath)
    with open(filepath, 'w') as f:
        f.write(string)
    return filepath
        
def upload_file_to_server(filename, remote_filename):
    print "Uploading file " + filename + " to server as " + remote_filename + " ..."
    url = ADMIN_URL + "/upload.php"
    files = {'userfile': (remote_filename, open(filename, 'rb'))}
    r = requests.post(url, files=files)
    response = r.text
    if 'error' in response:
        print "File upload to Server failed"
    elif 'success' in response:
        print "File uploaded to server successfully"
    
def get_kilobytes(val):
    try:
        int_value = int(val)
    except ValueError:
        return val
    kilo = int_value/ONE_KB
    return str(round(kilo, 3)) + " KB"
    
def get_megabytes(val):
    try:
        int_value = int(val)
    except ValueError:
        return val
    mega = int_value/ONE_MB
    return str(round(mega, 3)) + " MB"
    
def get_gigabytes(val):
    try:
        int_value = int(val)
    except ValueError:
        return val
    giga = int_value/ONE_GB
    return str(round(giga, 3)) + " GB"
    