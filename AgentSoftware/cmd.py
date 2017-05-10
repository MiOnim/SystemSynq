"""
This file has two classes: Cmd and CmdWmic
Both of the are used to encapsulate Windows Command Prompt commands.

"""

__author__ = "Mazharul Onim"

import os

"""
This class encapsulates all commands run in Windows Command Prompt.
The instance variables are:
    self.cmd    - the command string to be executed
    self.result - the result of the command
    
"""
class Cmd:

    def __init__(self, cmd):
        self.cmd = cmd
        self.result = ""
        
    def run(self):
        res = os.popen(self.cmd).read()
        self.result = res.replace("\n", "{newline}")
        return self
        
    def get_result(self):
        return self.result.strip()

        
"""
This class encapsulates the commands and results for "wmic"
commands run on Windows command prompt to get various system information.
The instance variables are:
    self.module - the alias used in wmic e.g. 'cpu', 'os'
    self.info   - information requested from alias e.g. "MaxClockSpeed"
    self.result - the result of the command   [inherited from superclass Cmd]
    self.cmd    - the command string to be executed
"""
class CmdWmic(Cmd):
    
    def __init__(self, module, info):
        self.module = module
        self.info = info
        self.cmd = "wmic " + self.module + " get " + self.info
        
    def get_result(self):
        return self.result.splitlines()[1].replace("{newline}", "").strip()
