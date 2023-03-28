<?php

/**
 * (c) 2004-2007 Linbox / Free&ALter Soft, http://linbox.com
 * (c) 2007-2009 Mandriva, http://www.mandriva.com
 * (c) 2015 Siveo, http://http://www.siveo.net
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
 * along with MMC.  If not, see <http://www.gnu.org/licenses/>.
 */

function xmlrpc_tests(){
    return xmlCall("updates.tests");
}

function xmlrpc_get_grey_list($start, $end, $filter=""){
    return xmlCall("updates.get_grey_list", [$start, $end, $filter]);
}

function xmlrpc_get_white_list($start, $end, $filter=""){
    return xmlCall("updates.get_white_list", [$start, $end, $filter]);
}

function xmlrpc_get_black_list($start, $end, $filter=""){
    return xmlCall("updates.get_black_list", [$start, $end, $filter]);
}

function xmlrpc_get_family_list($start, $end, $filter=""){
    return xmlCall("updates.get_family_list", [$start, $end, $filter]);
}

function xmlrpc_approve_update($updateid){
    return xmlCall("updates.approve_update", [$updateid]);
}

function xmlrpc_grey_update($updateid, $enabled=0){
    return xmlCall("updates.grey_update", [$updateid, $enabled]);
}

function xmlrpc_exclude_update($updateid){
    return xmlCall("updates.exclude_update", [$updateid]);
}

function xmlrpc_delete_rule($id){
    return xmlCall("updates.delete_rule", [$id]);
}

function xmlrpc_white_unlist_update($updateid){
    return xmlCall("updates.white_unlist_update", [$updateid]);
}

function xmlrpc_get_machine_with_update($kb){
    return xmlCall("updates.get_machine_with_update", [$kb]);
}

function xmlrpc_get_count_machine_as_not_upd($updateid){
    return xmlCall("updates.get_count_machine_as_not_upd", [$updateid]);
}
?>