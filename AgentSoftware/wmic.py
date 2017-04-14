import os
#import time
from datetime import datetime

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
        #self.run()
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
        
    def get_pretty_time(self):
        value = self.value.split('.')[0]
        #try:
        #    time_obj = time.strptime(value, "%Y%m%d%H%M%S")
        #except ValueError:
        #    return self.value
        #
        #return time.strftime("%Y/%m/%d %H:%M:%S", time_obj)
        try:
            dt = datetime.strptime(value, "%Y%m%d%H%M%S")
        except ValueError:
            return self.value
        
        return dt.strftime("%Y/%m/%d %H:%M:%S")