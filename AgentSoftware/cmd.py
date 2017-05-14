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
    
Only commands that are in the class variable 'WHITELIST' can be run

"""
class Cmd:

    WHITELIST = ["cd", "comp", "copy", "date", "dir",  "echo", "find", "findstr",
                 "hostname", "ipconfig", "mkdir", "more", "nslookup", "ping",
                 "robocopy", "time", "tracert", "wmic"]
    
    def __init__(self, cmd):
        self.cmd = cmd
        self.result = ""
        
    def run(self):
        if self.cmd.split(" ")[0] not in Cmd.WHITELIST:
            # command can be 'malicious' or 'not found'
            print "Command '" + self.cmd + "' is not allowed"
            self.result = "command not allowed"
        else:
            print "Running windows command: '" + self.cmd + "'"
            process = os.popen(self.cmd)
            res = process.read()
            process.close()
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
