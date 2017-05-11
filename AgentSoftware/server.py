"""
This file serves a lightweight server locally to accept request from the Admin Server.
Request from the Admin Server can be one of the following:
    Refresh Event Logs for this computer
    Refresh the database after querying System information again

"""

__author__ = "Mazharul Onim"

from BaseHTTPServer import BaseHTTPRequestHandler, HTTPServer
from urlparse import urlparse
import json
from agent import *
from utils import *
#from main import *

PORT = 80

class MyHandler(BaseHTTPRequestHandler):
    
    def do_GET(self):
        try:
            query = urlparse(self.path).query
            params = dict(qc.split("=") for qc in query.split("&"))
            if ('refresh' not in params):
                self.error_response("Invalid request. SyntaxError")
            else:
                if params['refresh'] == 'events':
                    rv = self.refresh_events(params)
                    if not rv:
                        self.error_response("Invalid Events query")
                    else:
                        self.success_response()
                elif params['refresh'] == 'database':
                    #main_run()
                    self.success_response()
                else:
                    print "Invalid param for 'refresh'"
                    self.error_response("Invalid request. SyntaxError")
        except Exception, e:
            print "An Exception occured in GET request: '%s'" % e
            self.error_response("Invalid request. SyntaxError")
        return
    
    def success_response(self):
        self.send_response(200)
        self.send_header('Content-type', 'application/json')
        self.end_headers()
        response = json.dumps({"success": "1"})
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
            return True



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
