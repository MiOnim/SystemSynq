"""
This file contains some utility functions to be used on other files

"""

__author__ = "Mazharul Onim"

from datetime import datetime

def pretty_print_time(value):
    value = value.split('.')[0]
    try:
        dt = datetime.strptime(value, "%Y%m%d%H%M%S")
    except ValueError:
        return value
    return dt.strftime("%Y/%m/%d %H:%M:%S")

def print_