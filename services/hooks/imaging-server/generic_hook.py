#!/usr/bin/python
# -*- coding: utf-8; -*-
#
# (c) 2010 Mandriva, http://www.mandriva.com
#
# $Id$
#
# This file is part of Pulse 2, http://pulse2.mandriva.org
#
# Pulse 2 is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# Pulse 2 is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Pulse 2. If not, see <http://www.gnu.org/licenses/>.
#

"""
    This is a generic hook skeleton to be used with pulse 2 imaging server
"""
import sys
import twisted.internet.reactor
import logging
import logging.config
import pulse2.apis.clients.imaging
import pulse2.imaging.config

CONFIG_FILE = '/etc/mmc/pulse2/imaging-server/imaging-server.ini' #: config file location

# default error code
ERROR_OK = 0        #: no error
ERROR_SERVER = 1    #: error server-side
ERROR_CLIENT = 2    #: error client-side (here)
ERROR_UNKNOWN = 3   #: unknow (and default) error
exitcode = ERROR_UNKNOWN #: global error code, used when exiting

########################################################################
#         NOTHING SHOULD BE ALTERED ABOVE THIS LINE                    #
########################################################################

def myCall():
    """
        Design your own call here

        a deferred should be passed to callFunction()
        @woot
    """
    callFunction(
        imagingAPI.imagingServerStatus(
        )
    )

def myTreatment(result):
    """
        Design your own treatment here

        don't forget to set exitcode and finally call endBack()
    """
    global exitcode
    if result:
        exitcode= ERROR_OK
    else:
        exitcode = ERROR_SERVER
    endBack()

########################################################################
#         NOTHING SHOULD BE ALTERED BELOW THIS LINE                    #
########################################################################

def endBack():
    """
        take the reactor down
    """
    twisted.internet.reactor.callLater(0, twisted.internet.reactor.stop)

def callBack(result):
    """
        XMLRPC result treatment

        check if it was a success by analysing result
        if it is a success, call myCall(result)
        if not, exitcode is set to ERROR_SERVER then call endBack()
    """

    global exitcode
    # if result is a list and the first arg a string and its value,
    # 'PULSE2_ERR', then something went wrong
    if type(result) == list and type(result[0]) == str and result[0] == 'PULSE2_ERR':
        logging.getLogger().error("%s : Error code = %d (see previous line)" % (sys.argv[0], result[1]))
        exitcode = ERROR_SERVER
        endBack()
    else:
        logging.getLogger().debug("%s : No error" % (sys.argv[0]))
        exitcode = ERROR_CLIENT
        myTreatment(result)

def errorBack(reason):
    """
        XMLRPC error treatment

        just set exitcode to ERROR_CLIENT then call endBack()
    """
    global exitcode
    exitcode = ERROR_CLIENT
    endBack()

def callFunction(deffered):
    """
        XMLRPC request handling

        attach callBack() and errorBack() to the deferred
    """
    deffered.addCallbacks( # deferred handling
        callBack,
        errorBack
    )

# Parse the command line
config = pulse2.imaging.config.ImagingConfig(CONFIG_FILE) #: ConfigParser object
config.setup()
logging.config.fileConfig(CONFIG_FILE)

# Instanciate the API
imagingAPI = pulse2.apis.clients.imaging.ImagingApi({
    "server" : config.pserver_host,
    "port" : config.pserver_port,
    "mountpoint" : config.pserver_mount_point,
    "enablessl" : config.pserver_enablessl,
    "username" : config.pserver_username,
    "password" : config.pserver_password,
    "verifypeer" : config.pserver_verifypeer,
    "localcert" : config.pserver_localcert,
    "cacert" : config.pserver_cacert,
}) #: Object which will be used to speak with our pserver

# fire the reactor
twisted.internet.reactor.callWhenRunning(myCall)
twisted.internet.reactor.run()
sys.exit(exitcode)
