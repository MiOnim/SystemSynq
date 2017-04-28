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
        query = """ UPDATE Networks
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
        
    def close(self):
        self.cur.close()
        self.db.close()