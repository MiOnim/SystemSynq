"""
This class encapsulates the commands and results for "wmic" 
commands run on Windows command prompt to get various system information.

    self.module - the alias used in wmic e.g. 'cpu', 'os'
    self.info   - information requested from alias e.g. "MaxClockSpeed"
    self.value  - the result of the command
    self.cmd    - the command string to be executed

Author: Mazharul Onim
"""

import os

class Wmic:
    
    def __init__(self, module, info):
        self.module = module
        self.info = info
        self.value = ""
        self.cmd = "wmic " + self.module + " get " + self.info
        
    def run(self):
        res = os.popen(self.cmd).read()
        self.value = res.splitlines()[1]
        return self
        
    def get_value(self):
        return self.value
        
    def get_kilobytes(self):
        try:
            int_value = int(self.value)
        except ValueError:
            return self.value
        
        kilo = int_value/1024
        return str(round(kilo, 3)) + " KB"
        
    def get_megabytes(self):
        try:
            int_value = int(self.value)
        except ValueError:
            return self.value
        
        mega = int_value/1.048576e6
        return str(round(mega, 3)) + " MB"
        
    def get_gigabytes(self):
        try:
            int_value = int(self.value)
        except ValueError:
            return self.value
        
        giga = int_value/1.073741824e9
        return str(round(giga, 3)) + " GB"
        