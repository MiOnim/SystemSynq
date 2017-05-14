"""
This file serves a lightweight server locally to accept request from the Admin Server.
Request from the Admin Server can be one of the following:
    Refresh Event Logs for this computer
    Refresh the database after querying System information again
    Run a command (only whitelisted) in the Command Prompt

"""

__author__ = "Mazharul Onim"

from BaseHTTPServer import BaseHTTPRequestHandler, HTTPServer
from urlparse import urlparse
import json
import time
from agent import *
from utils import *
from cmd import *
from main import *

PORT = 80

class MyHandler(BaseHTTPRequestHandler):
    
    def do_GET(self):
        success_events = False   # whether GET Request for refreshing events is successful
        success_database = False # whether GET Request for refreshing database is successful
        success_cmd = False      # whether GET Request for running command in CMD is successful
        
        start_time = time.time()  # to time the GET request
        try:
            query = urlparse(self.path).query
            params = dict(qc.split("=") for qc in query.split("&"))
            if ('refresh' not in params) and ('cmd' not in params):
                self.error_response("Invalid request. SyntaxError")
            else:
                # .get() returns 'None' if not found in the dictionary
                refresh = params.get('refresh')
                cmd = params.get('cmd')
                if refresh == 'events':
                    rv = self.refresh_events(params)
                    if rv is False:
                        self.error_response("Invalid Events query")
                    else:
                        success_events = True
                elif refresh == 'database':
                    main_run()
                    success_database = True
                elif cmd:
                    cmd_obj = Cmd(cmd).run()
                    cmd_result = cmd_obj.get_result()
                    success_cmd = True
                else:
                    print "Invalid GET param"
                    self.error_response("Invalid request. SyntaxError")
        except Exception, e:
            print "An Exception occured in GET request: '%s'" % e
            self.error_response("Invalid request. SyntaxError")
        ## request successful. Send response:
        end_time = time.time()
        time_taken = end_time - start_time
        if success_events:
            # 'rv' stores the number of events
            message = "%d events found in %.2f seconds" % (rv, time_taken)
            self.success_response(message)
        elif success_database:
            message = "(%.2f seconds taken)" % (time_taken)
            self.success_response(message)
        elif success_cmd:
            self.success_response(cmd_result)
        return
    
    def success_response(self, message='1'):
        self.send_response(200)
        self.send_header('Content-type', 'application/json')
        self.end_headers()
        response = json.dumps({"success": message})
        print "response sent: " + response
        self.wfile.write(response)
    
    def error_response(self, message):
        self.send_response(400)
        self.send_header('Content-type', 'application/json')
        self.end_headers()
        response = json.dumps({"error": message})
        print "response sent: " + response
        self.wfile.write(response)
        
    def refresh_events(self, params):
        logfile = params.get('logfile')
        eventid = params.get('eventid')
        evttype = params.get('evttype')
        event = EventViewer(logfile, eventid, evttype).run()
        if not event.get_result():
            print "No events found"
            return False
        else:
            logs = event.serialize()
            filename = write_to_file(logs, ".\\events\\")
            remote_filename = "events-" + ID + ".txt"
            upload_file_to_server(filename, remote_filename)
            return event.get_num_events()



"""
Start the server:
"""
try:
    server = HTTPServer(('', PORT), MyHandler)
    print "Started httpserver on port " + str(PORT)
    server.serve_forever()
    
except KeyboardInterrupt:
    print "Shutting down the web server"
    server.shutdown()
    server.socket.close()
