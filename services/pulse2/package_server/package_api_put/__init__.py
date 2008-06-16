#!/usr/bin/python
# -*- coding: utf-8; -*-
#
# (c) 2007-2008 Mandriva, http://www.mandriva.com/
#
# $Id: __init__.py 30 2008-02-08 16:40:54Z nrueff $
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
# along with Pulse 2; if not, write to the Free Software
# Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
# MA 02110-1301, USA.

"""
    Pulse2 Modules
"""
import twisted.web.html
import twisted.web.xmlrpc
import logging
from pulse2.package_server.package_api_get import PackageApiGet
from pulse2.package_server.types import Package
from pulse2.package_server.common import Common

class PackageApiPut(PackageApiGet):
    type = 'PackageApiPut'

    def xmlrpc_putPackageDetail(self, package):
        pa = Package()
        pa.fromH(package)

        ret = Common().editPackage(package['id'], pa)
        if not ret: return False

        ret = Common().writePackageTo(package['id'], self.mp)
        if not ret: return False

        ret = Common().associatePackage2mp(package['id'], self.mp)
        if not ret: return False

        return package['id']

    def xmlrpc_putPackageLabel(self, pid, label):
        self.logger.warn("(%s) %s : call to an unimplemented method"%(self.type, self.name))

    def xmlrpc_putPackageVersion(self, pid, version):
        self.logger.warn("(%s) %s : call to an unimplemented method"%(self.type, self.name))

    def xmlrpc_putPackageSize(self, pid, size):
        self.logger.warn("(%s) %s : call to an unimplemented method"%(self.type, self.name))

    def xmlrpc_putPackageInstallInit(self, pid, cmd):
        self.logger.warn("(%s) %s : call to an unimplemented method"%(self.type, self.name))

    def xmlrpc_putPackagePreCommand(self, pid, cmd):
        self.logger.warn("(%s) %s : call to an unimplemented method"%(self.type, self.name))

    def xmlrpc_putPackageCommand(self, pid, cmd):
        self.logger.warn("(%s) %s : call to an unimplemented method"%(self.type, self.name))

    def xmlrpc_putPackagePostCommandSuccess(self, pid, cmd):
        self.logger.warn("(%s) %s : call to an unimplemented method"%(self.type, self.name))

    def xmlrpc_putPackagePostCommandFailure(self, pid, cmd):
        self.logger.warn("(%s) %s : call to an unimplemented method"%(self.type, self.name))

    def xmlrpc_putPackageFiles(self, pid, a_files):
        self.logger.warn("(%s) %s : call to an unimplemented method"%(self.type, self.name))

    def xmlrpc_addPackageFile(self, pid, file):
        self.logger.warn("(%s) %s : call to an unimplemented method"%(self.type, self.name))

