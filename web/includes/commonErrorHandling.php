<?php
/**
 * (c) 2004-2006 Linbox / Free&ALter Soft, http://linbox.com
 *
 * $Id$
 *
 * This file is part of LMC.
 *
 * LMC is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * LMC is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with LMC; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
?>
<?php

$errObj = new ErrorHandlingControler();

$errItem = new ErrorHandlingItem('(ldap.ALREADY_EXISTS|Already exist)');
$errItem->setMsg(_("This item already exists in your LDAP directory"));
$errItem->setAdvice(_("Solve the problem by:
                        <ul>
                            <li>change this entry name</li>
                            <li>delete this entry before recreate it</li>
                        </ul>"));
$errItem->setTraceBackDisplay(false);
$errItem->setSize(300);
$errItem->setLevel(0);
$errObj->add($errItem);


$errItem = new ErrorHandlingItem('Can\'t contact LDAP server');
$errItem->setMsg(_("LMC Agent can't contact your LDAP server"));
$errItem->setAdvice(_("Solve the problem by:
                        <ul>
                            <li>Verify your LDAP server is correctly configured in /etc/lmc/plugins/base/ini </li>
                            <li>Verify you LDAP server is up</li>
                        </ul>"));

$errObj->add($errItem);



$errItem = new ErrorHandlingItem('(exceptions.IndexError: list index out of range|ldap.NO_SUCH_OBJECT)');
$errItem->setMsg(_("This items seems to not be in the index"));
$errItem->setAdvice(_("This problem can appear if:
                        <ul>
                            <li>This item no longer exists.</li>
                            <li>You misspelled it.</li>
                        </ul>"));
//$errItem->setTraceBackDisplay(false);
$errItem->setSize(800);
$errItem->setLevel(0);
$errObj->add($errItem);


$errItem = new ErrorHandlingItem('Failed to modify password entry');
$errItem->setMsg(_("smbpasswd failed to change your password entry"));
$errItem->setAdvice(_("Verify that your smbpasswd is correctly configures:
                        <ul>
                            <li> Your Ldap server can be down</li>
                            <li> Your Samba server is not properly configure</li>
                        </ul>"));

$errObj->add($errItem);

?>