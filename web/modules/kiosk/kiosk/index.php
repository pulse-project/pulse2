<?php
/**
 * (c) 2016 Siveo, http://siveo.net
 * $Id$
 *
 * This file is part of Management Console (MMC).
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


require("modules/kiosk/graph/index.css");
require_once("modules/kiosk/includes/xmlrpc.php");
require_once("modules/pulse2/includes/utilities.php");
require("graph/navbar.inc.php");
require("modules/kiosk/kiosk/localSidebar.php");

$p = new PageGenerator(_T("List of profils",'kiosk'));
$p->setSideMenu($sidemenu);
$p->display();

$profiles = xmlrpc_get_profiles_list();

$profiles_name = [];
$profiles_date = [];
$profiles_status = [];


foreach($profiles as $element)
{
    $profiles_name[] = $element['name'];
    $profiles_status[] = ($element['active'] == 1) ? _T("Active","kiosk") : _T("Inactive","kiosk");
}

$n = new OptimizedListInfos($profiles_name, _T("Profile Name", "kiosk"));
$n->disableFirstColumnActionLink();
$n->addExtraInfo($profiles_status, _T("Profile Status", "kiosk"));

// parameters are :
// - label
// - action
// - class (icon)
// - profile get parameter
// - module
// - submodule
$action_editPackage = new ActionItem(_T("Associate Packages", 'kiosk'),"editPackages","list","profile","kiosk", "kiosk");
$action_editUsers = new ActionItem(_T("Associate Users", 'kiosk'),"editUsers","users","profile","kiosk", "kiosk");
$action_editProfiles = new ActionItem(_T("Edit Profil",'kiosk'), "editProfile", "edit", "profile", "kiosk", "kiosk");
$action_deleteProfil = new ActionItem(_T("Delete Profil",'kiosk'), "delete", "delete", "profile", "kiosk", "kiosk");
$n->addActionItem($action_editPackage);
$n->addActionItem($action_editUsers);
$n->addActionItem($action_editProfiles);
$n->addActionItem($action_deleteProfil);
$n->setNavBar(new AjaxNavBar($count, $filter1));

$n->display();
?>
