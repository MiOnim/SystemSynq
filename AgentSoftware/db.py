"""
This class provides an interface to the server Database.
It contains all the necessary queries that will be needed by the agent.

"""

__author__ = "Mazharul Onim"

import MySQLdb

HOST = "10.22.12.139"
USERNAME = "agent"
PASSWORD = "systemsynq"
DATABASE = "systemsynq"

class Db:
    
    def __init__(self):
        self.db = MySQLdb.connect(HOST, USERNAME, PASSWORD, DATABASE)
        self.cur = self.db.cursor()
        
    def add_new_information(self, id, name):
        query = "INSERT INTO information (id, name) VALUES ('%s', '%s')" % (id, name)
        print "Inserting new computer in 'information' table"
        try:
            self.cur.execute(query)
            self.db.commit()
        except Exception, e:
            print "Failed to update database: add_new_information(): %s" % e
    
    def update_information(self, name, os, arch, mac, cores, clock_speed, ram_total, disk_total):
        query = """ UPDATE information 
                    SET os='%s', arch='%s', mac='%s', cores='%s', clock_speed='%s', ram_total='%s', disk_total='%s' 
                    WHERE name='%s'
                """ % (os, arch, mac, cores, clock_speed, ram_total, disk_total, name)
        print "Updating the 'information' table"
        try:
            self.cur.execute(query)
            self.db.commit()
        except Exception, e:
            print "Failed to update database: update_information(): %s" % e
        
    def add_new_status(self, id, name):
        query = "INSERT INTO status (id, name) VALUES ('%s', '%s')" % (id, name)
        print "Inserting new computer in 'status' table"
        try:
            self.cur.execute(query)
            self.db.commit()
        except Exception, e:
            print "Failed to update database: add_new_status(): %s" % e
    
    def update_status(self, name, ip, last_shutdown, last_bootup):
        query = """ UPDATE status 
                    SET ip='%s', last_shutdown='%s', last_bootup='%s' 
                    WHERE name='%s'
                """ % (ip, last_shutdown, last_bootup, name)
        print "Updating the 'status' table"
        try:
            self.cur.execute(query)
            self.db.commit()
        except Exception, e:
            print "Failed to update database: update_status(): %s" % e
        
    def add_new_setting(self, id):
        query = "INSERT INTO settings (id) VALUES ('%s')" % (id)
        print "Inserting new computer in 'settings' table"
        try:
            self.cur.execute(query)
            self.db.commit()
        except Exception, e:
            print "Failed to update database: add_new_settings(): %s" % e
    
    def update_setting(self, id, min_free_disk, min_free_ram, max_process):
        query = """ UPDATE settings 
                    SET min_free_disk='%s', min_free_ram='%s', max_process='%s' 
                    WHERE id='%s'
                """ % (min_free_disk, min_free_ram, max_process, id)
        print "Updating the 'settings' table"
        try:
            self.cur.execute(query)
            self.db.commit()
        except Exception, e:
            print "Failed to update database: update_settings(): %s" % e
        
    def insert_into_running(self, name, cpu_usage, ram_free, disk_free, process, users_logged):
        query = """ INSERT INTO running (name, cpu_usage, ram_free, disk_free, process, users_logged)
                    VALUES ('%s','%s','%s','%s','%s','%s')
                """ % (name, cpu_usage, ram_free, disk_free, process, users_logged)
        print "Inserting into the 'running' table"
        try:
            self.cur.execute(query)
            self.db.commit()
        except Exception, e:
            print "Failed to update database: insert_into_running(): %s" % e
        
    def insert_into_alerts(self, id, name, alert, priority):
        query = """ INSERT INTO alerts (id, name, alert, priority, alert_time)
                    VALUES ('%s', '%s', '%s', '%s', NOW())
                """ % (id, name, alert, priority)
        print "Inserting into the 'alerts' table"
        try:
            self.cur.execute(query)
            self.db.commit()
        except Exception, e:
            print "Failed to update database: insert_into_alerts(): %s" % e
        
    def close(self):
        self.cur.close()
        self.db.close()
        