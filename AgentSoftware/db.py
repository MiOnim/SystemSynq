import MySQLdb

HOST = "10.22.13.191"
USERNAME = "agent"
PASSWORD = "systemsynq"
DATABASE = "systemsynq"

class Db:
    
    def __init__(self):
        self.db = MySQLdb.connect(HOST, USERNAME, PASSWORD, DATABASE)
        self.cur = self.db.cursor()
        
    def update_network(self, id, uptime, shutdown, login, usb, user):
        query = """ UPDATE Network
                    SET Uptime='%s', Recent_Shutdowns='%s', Recent_Logins='%s', 
                        USB_Devices='%s', User_Log='%s'
                    WHERE ID='%s'
                """ % (uptime, shutdown, login, usb, user, id)
        print "Updating the Network table"
        try:
            self.cur.execute(query)
            self.db.commit()
        except Exception, e:
            print "Failed to update database: update_network(): %s" % e
        
    def insert_into_hardwaresoftware(self, name, cores, clock_speed, temp, cpu_usage, ram_total, ram_free, disk_total, disk_free, process):
        query = """ INSERT INTO HardwareSoftware (Name, CPU_Cores, Clock_Speed, CPU_Temp, CPU_Usage,
                                                  RAM_Total, RAM_Free, DISK_Total, DISK_Free, Processes_Total)
                    VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')
                """ % (name, cores, clock_speed, temp, cpu_usage, ram_total, ram_free, disk_total, disk_free, process)
        print "Inserting into the HardwareSoftware table"
        try:
            self.cur.execute(query)
            self.db.commit()
        except Exception, e:
            print "Failed to update database: update_network(): %s" % e
        
    def close(self):
        self.cur.close()
        self.db.close()