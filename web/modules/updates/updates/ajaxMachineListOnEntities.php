<?php
/**
 * (c) 2022 Siveo, http://siveo.net/
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
require_once("modules/updates/includes/xmlrpc.php");
require_once("modules/glpi/includes/xmlrpc.php");
require_once("modules/xmppmaster/includes/xmlrpc.php");
require_once("modules/base/includes/computers.inc.php");

$location = (isset($_GET['location'])) ? $_GET['location'] : "";
$gid = (isset($_GET['gid'])) ? $_GET['gid'] : "";
$groupname = (isset($_GET['groupname'])) ? $_GET['groupname'] : "";
$filter = "Microsoft";
$field = "platform";
$contains = (isset($_GET['contains'])) ? $_GET['contains'] : "";

$start = (isset($_GET['start'])) ? $_GET['start'] : 0;
$maxperpage = (isset($_GET['maxperpage'])) ? $_GET['maxperpage'] : $config['maxperpage'];
$end = (isset($_GET['end'])) ? $_GET['end'] : $maxperpage - 1;

$ctx = [];
$ctx['location'] = $location;
$ctx['filter'] = $filter;
$ctx['field'] = $field;
$ctx['contains'] = $contains;

$ctx['start'] = $start;
$ctx['end'] = $end;
$ctx['maxperpage'] = $maxperpage;

$uuid = htmlspecialchars($_GET['uuid']);
$ctx['uuid'] = $uuid;

$detailsByMach = new ActionItem(_T("Test", "updates"),"detailsByMachines","display","", "updates", "updates");

if ($uuid == '')
{
    $typeOfDetail = "group";
}
else
{
    $typeOfDetail = "entitie";
}

$machines = xmlrpc_xmppmaster_get_machines_list($start, $end, $ctx);
$filterGid = array('gid' => $gid);
$listGroup = getRestrictedComputersList(0, -1, $filterGid, False);

$group_array = [];

if ($typeOfDetail == "group")
{
    print_r(_T("<h2>Global compliance rate for ".$groupname."</h2>", "updates"));
    echo "<br>";

    foreach ($listGroup as $key => $value)
    {
        array_push($group_array, $key);
    }

    $group_compliance = xmlrpc_get_conformity_update_for_group($group_array);

    print_r("--------------------------------");
    print_r($group_compliance);
    print_r("--------------------------------");

    switch(intval($group_compliance)){
        case $group_compliance <= 10:
            $color = "#ff0000";
            break;
        case $group_compliance <= 20:
            $color = "#ff3535";
            break;
        case $group_compliance <= 30:
            $color = "#ff5050";
            break;
        case $group_compliance <= 40:
            $color = "#ff8080";
            break;
        case $group_compliance <  50:
            $color = "#ffA0A0";
            break;
        case $group_compliance <=  60:
            $color = "#c8ffc8";
            break;
        case $group_compliance <= 70:
            $color = "#97ff97";
            break;
        case $group_compliance <= 80:
            $color = "#64ff64";
            break;
        case $group_compliance <=  90:
            $color = "#2eff2e";
            break;
        case $group_compliance >90:
            $color = "#00ff00";
            break;
    }
    echo "<div class='progress' style='width: ".$group_compliance."%; background : ".$color."; font-weight: bold; color : black; text-align: right;'> ".$group_compliance."% </div>";
}

echo "<br>";
echo "<br>";

if ($typeOfDetail == "group")
{
    print_r(_T("<h2>Computers from group ".$groupname."</h2>","updates"));
}
else
{
    print_r(_T("<h2>Computers from entitie </h2>","updates"));
}

// A VOIR SI JE M'EN SORS AVEC DES BOUCLES MAIS PEUT ETRE REFAIRE UNE REQUETE EN FONCTION DE L'ENTITYID
// POUR POUVOIR COMPARER L UUID AVEC ENTITYID
if ($uuid != '')
{
    preg_match("/UUID([0-9]+)/", $uuid, $matches);
    $match = (int)$matches[1];
}

$params = [];
$machineNames = [];
$complRates = [];
$detailsByMachs = [];
$machineByEntitie = [];
$platform = [];
// TOTAL NOMBRE DE LIGNE REQUETE
if ($uuid != '')
{
    $count = $machines['count'];
}

$all_grey_enable = xmlrpc_get_count_grey_list_enable();
$all_grey_enable = $all_grey_enable['0']['enable_grey'];

if ($typeOfDetail == "entitie")
{
    for($i=0; $i < $count; $i++){
        if($machines['data']['entityid'][$i] == $match){
            $detailsByMachs[] = $detailsByMach;
            $machineNames[] = $machines['data']['hostname'][$i];
            $machineByEntitie[] = $machines['data']['glpi_entity_id'][$i];

            $compliance_computer = xmlrpc_get_conformity_update_by_machine($machines['data']['id'][$i]);
            $comp = $compliance_computer['0']['update_waiting'];

            if ($all_grey_enable != '0' and $comp != '0')
            {
                $comp = $comp / $all_grey_enable * 100;
            }
            else
            {
                $comp = '0';
            }

            switch(intval($comp)){
                case $comp <= 10:
                    $color = "#ff0000";
                    break;
                case $comp <= 20:
                    $color = "#ff3535";
                    break;
                case $comp <= 30:
                    $color = "#ff5050";
                    break;
                case $comp <= 40:
                    $color = "#ff8080";
                    break;
                case $comp <  50:
                    $color = "#ffA0A0";
                    break;
                case $comp <=  60:
                    $color = "#c8ffc8";
                    break;
                case $comp <= 70:
                    $color = "#97ff97";
                    break;
                case $comp <= 80:
                    $color = "#64ff64";
                    break;
                case $comp <=  90:
                    $color = "#2eff2e";
                    break;
                case $comp >90:
                    $color = "#00ff00";
                    break;
            }
            $complRates[] = "<div class='progress' style='width: ".$comp."%; background : ".$color."; font-weight: bold; color : black; text-align: right;'> ".$comp."% </div>";

            $platform[] = $machines['data']['platform'][$i];
        }
        // TOTAL LIGNE APRES COMPARAISON
        $count_machineNames = count($machineNames);
    }
}

if ($typeOfDetail == "group")
{
    foreach ($listGroup as $k => $v) {
        $detailsByMachs[] = $detailsByMach;
        $machineNames[] = $v[1]['cn'][0];
        $machineByEntitie[] = $v[1]['objectUUID'][0];
        
        $platform[] = $v[1]['os'];
    }        
}

echo '<pre>';
// print_r($ctx);
// var_dump($filter);
// var_dump($count);
// print_r($machines);
// var_dump($match);
// var_dump($count_machineNames);
// print_r($machines['data']);
//print_r($machines['data']['hostname']);
//print_r($machines['data']['entityid']);
//print_r($machines['data']['platform']);
// print_r($machineByEntitie);
// print_r($compliancerate);
// print_r($test);
echo '</pre>';


$n = new OptimizedListInfos($machineNames, _T("Name machine", "updates"));
$n->disableFirstColumnActionLink();
$n->addExtraInfo($platform, _T("Plateform", "updates"));
$n->addExtraInfo($complRates, _T("Compliance rate", "updates"));
$n->addExtraInfo($machineByEntitie, _T("Enitie_machine", "updates"));
$n->addActionItemArray($detailsByMachs);

$n->setItemCount($count_machineNames);
$n->setNavBar(new AjaxNavBar($count_machineNames, $ctx['filter']));
$n->setParamInfo($params);
$n->display();
?>