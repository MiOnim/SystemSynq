"""
This class provides an interface to the server Database.
It contains all the necessary queries that will be needed by the agent.

"""

__author__ = "Mazharul Onim"

import MySQLdb

HOST = "10.22.13.191"
USERNAME = "agent"
PASSWORD = "systemsynq"
DATABASE = "systemsynq"

class Db:
    
    def __init__(self):
        self.db = MySQLdb.connect(HOST, USERNAME, PASSWORD, DATABASE)
        self.cur = self.db.cursor()
        
    def add_new_information(self, name):
        query = "INSERT INTO information (name) VALUES (%s)" % (name)
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
        
    def add_new_status(self, name):
        query = "INSERT INTO status (name) VALUES (%s)" % (name)
        print "Inserting new computer in 'status' table"
        try:
            self.cur.execute(query)
            self.db.commit()
        except Exception, e:
            print "Failed to update database: add_new_status(): %s" % e
    
    def update_status(self, name, ip, on_off, last_shutdown, last_bootup):
        query = """ UPDATE status 
                    SET ip='%s', on_off='%s', last_shutdown='%s', last_bootup='%s' 
                    WHERE name='%s'
                """ % (ip, on_off, last_shutdown, last_bootup, name)
        print "Updating the 'status' table"
        try:
            self.cur.execute(query)
            self.db.commit()
        except Exception, e:
            print "Failed to update database: update_status(): %s" % e
        
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
        
    def close(self):
        self.cur.close()
        self.db.close()
        