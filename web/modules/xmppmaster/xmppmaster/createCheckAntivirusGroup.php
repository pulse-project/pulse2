<?php
/**
 * (c) 2004-2007 Linbox / Free&ALter Soft, http://linbox.com
 * (c) 2007-2012 Mandriva, http://www.mandriva.com
 * (c) 2021 Siveo, http://siveo.net
 *
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

require_once("modules/dyngroup/includes/dyngroup.php"); # for Group Class
require_once("modules/xmppmaster/includes/xmlrpc.php");
require_once("modules/dyngroup/includes/xmlrpc.php");

if ($_GET['group'] == 'enabled_antivirus') {
    $groupname = sprintf (_T("Antivirus status is enabled at %s", "xmppmaster"), date("Y-m-d H:i:s"));
}
elseif ($_GET['group'] == 'disabled_antivirus') {
    $groupname = sprintf (_T("Antivirus is disabled or not up-to-date at %s", "xmppmaster"), date("Y-m-d H:i:s"));
}
elseif ($_GET['group'] == 'antivirus_name_active') {
    $groupname = sprintf (_T("Computers with the active antivirus %s at %s", "xmppmaster"), htmlspecialchars($_GET['antivirus']), date("Y-m-d H:i:s"));
}
elseif ($_GET['group'] == 'antivirus_name') {
    $groupname = sprintf (_T("Computers with the specific antivirus %s at %s", "xmppmaster"), htmlspecialchars($_GET['antivirus']), date("Y-m-d H:i:s"));
}
elseif ($_GET['group'] == 'last_scan') {
    $groupname = sprintf (_T("Computers with a '%s' scan at %s", "xmppmaster"), htmlspecialchars($_GET['scan']), date("Y-m-d H:i:s"));
}

$antivirusName = $_GET['antivirus'] ?? '';
$lastScanDate = $_GET['scan'] ?? '';
$groupType = $_GET['group'];
$groupmembers = xmlrpc_get_antivirus_machines($groupType, $antivirusName, $lastScanDate);

$group = new Group();
$group->create($groupname, False);
$group->addMembers($groupmembers);

$truncate_limit = getMaxElementsForStaticList();
if ($truncate_limit == safeCount($groupmembers)) new NotifyWidgetWarning(sprintf(_T("Computers list has been truncated at %d computers", "dyngroup"), $truncate_limit));

header("Location: " . urlStrRedirect("base/computers/display", array('gid'=>$group->id, 'groupname'=>$groupname)));
exit;
