<?php

/**
 * (c) 2004-2007 Linbox / Free&ALter Soft, http://linbox.com
 * (c) 2007-2013 Mandriva, http://www.mandriva.com
 *
 * $Id$
 *
 * This file is part of Mandriva Management Console (MMC).
 *
 * MMC is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * MMC is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with MMC; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
?>
<?php

require_once("modules/update/includes/xmlrpc.inc.php");
require_once("modules/update/includes/html.inc.php");
require("modules/base/computers/localSidebar.php");
require_once('graph/navbar.inc.php');
require_once("modules/pulse2/includes/utilities.php");

$MMCApp = & MMCApp::getInstance();
$os_classes = get_os_classes();
right_top_shortcuts_display();

if ($_GET['gid']) {
    $p = new TabbedPageGenerator();
    $p->setSideMenu($sidemenu);
    require("modules/dyngroup/includes/includes.php");
    $group = new Group($_GET['gid'], true, true);
    if ($group->exists == False) {
        print _T("This group doesn't exist", "update");
    } else {
        $countTab=1;
        $p->addTop(sprintf(_T("%s's group update manager", 'update'), $group->getName()), "modules/msc/msc/header.php");
        $p->addTab($countTab,_T('All updates', 'update'),"", "modules/update/update/viewUpdates.php", array('gid' => $_GET['gid']));
        foreach ($os_classes['data'] as $os) {
            $countTab++;
            $p->addTab($countTab,_T($os['name'], 'update'),"", "modules/update/update/viewUpdates.php", array('gid' => $_GET['gid'],'os_class_id'=>$os['id']));
        }
        $countTab++;
        $p->addTab($countTab,_T('Settings', 'update'),"", "modules/update/update/settings.php", array('gid' => $_GET['gid']));
#        $p->addTab("", _T("Logs", 'msc'), "", "modules/msc/msc/logs.php", array('gid' => $_GET['gid']));
    }
    $p->display();
} else {
    $p = new PageGenerator();
    $p->setSideMenu($sidemenu);
    $p->display();
    print _T("Not enough information", "update");
}
