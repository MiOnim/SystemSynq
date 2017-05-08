"""
This class encapsulates the commands and results for "wmic" 
commands run on Windows command prompt to get various system information.
The instance variables are:
    self.module - the alias used in wmic e.g. 'cpu', 'os'
    self.info   - information requested from alias e.g. "MaxClockSpeed"
    self.result - the result of the command
    self.cmd    - the command string to be executed

"""

__author__ = "Mazharul Onim"

import os

class CmdWmic:
    
    def __init__(self, module, info):
        self.module = module
        self.info = info
        self.result = ""
        self.cmd = "wmic " + self.module + " get " + self.info
        
    def run(self):
        res = os.popen(self.cmd).read()
        self.result = res.splitlines()[1]
        return self
        
    def get_result(self):
        return self.result
        