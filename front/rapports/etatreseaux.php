<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2015 Teclib'.

 http://glpi-project.org

 based on GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2014 by the INDEPNET Development Team.
 
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI (Wilaya plugin).

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

/** @file
* @brief
*/

include ("../../../../inc/includes.php");
include ("../../../../config/config.php");

//Session::checkRight("config", "w");

Html::header("État des connexions réseaux - Wilaya", $_SERVER['PHP_SELF'],"plugins");
PluginWilayaMenu::displayMenu();
PluginWilayaReport::displaySubMenu();

echo "<center>".
     "<ul>".
     "<li class='filter-btn'><a href=".$CFG_GLPI['root_doc']."/plugins/wilaya/front/rapports/etatreseaux.php?linked><i class=\"fa fa-check fa-2x\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;Ordinateurs liés</a></li>".
     "<li class='filter-btn'><a href=".$CFG_GLPI['root_doc']."/plugins/wilaya/front/rapports/etatreseaux.php?failed><i class=\"fa fa-close fa-2x\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;Ordinateurs non liés</a></li>".
     "</ul>".
     "</center><br>";

$USEDBREPLICATE         = 1;
$DBCONNECTION_REQUIRED  = 0;

$page_name="etatreseaux.php"; //  If you use this code with a different page ( or file ) name then change this 
$start=$_GET['start'];

if(strlen($start) > 0 and !is_numeric($start)){
    echo "Data Error";
    exit;
}

$eu = ($start - 0);
$limit = 10;
$this1 = $eu + $limit;
$back = $eu - $limit;
$next = $eu + $limit;

if(isset($_GET['linked'])) {
echo "<div class='center'>";
echo "<table class='tab_cadrehov' cellpadding='5'>\n";
echo "<tr class='tab_bg_1 center'>".
      "<th colspan='8'>" . __("État des connexions réseaux (Ordinateurs)") .
      "</th></tr>\n";

echo "<tr class='tab_bg_2'><th>Nom Machine</th>" .
      "<th>Interface Machine</th>".
      "<th>Adresse MAC</th>".
      "<th>Adresse IP</th>".
      "<th>Switch</th>".
      "<th>Interface Switch</th>".
      "<th>Date de liaison</th>".
      "<th>Événement</th></tr>\n";


$query = "SELECT SQL_CALC_FOUND_ROWS c.id AS IDC ,c.name AS UC,  np.name AS CInterface , np.mac AS addrMAC , ip.name AS addrIP ,
       (SELECT npnp.networkports_id_2 from glpi_networkports_networkports npnp WHERE npnp.networkports_id_1=np.id) AS idInterface,
       (SELECT ne.name FROM glpi_networkequipments ne WHERE ne.id=(SELECT n.items_id FROM glpi_networkports n WHERE n.id=(SELECT npnp.networkports_id_2 from glpi_networkports_networkports npnp WHERE npnp.networkports_id_1=np.id))) AS Switch,
       (SELECT n.name FROM glpi_networkports n WHERE n.id=(SELECT npnp.networkports_id_2 from glpi_networkports_networkports npnp WHERE npnp.networkports_id_1=np.id)) AS SInterface,
       (SELECT e.date FROM glpi_events e WHERE e.type='networkport' and e.date <> '' and e.items_id=np.id order by 1 desc LIMIT 1)  AS DateLiaison ,
       (SELECT e.message FROM glpi_events e WHERE e.type='networkport' and e.message <> '' and e.items_id=np.id order by 1 desc LIMIT 1) AS EVENT,
       (SELECT neq.id FROM glpi_networkequipments neq WHERE neq.id = (SELECT n.items_id FROM glpi_networkports n WHERE n.id=(SELECT npnp.networkports_id_2 from glpi_networkports_networkports npnp WHERE npnp.networkports_id_1=np.id))) AS idSwitch,
       (SELECT n.id FROM glpi_networkports n WHERE n.id=(SELECT npnp.networkports_id_2 from glpi_networkports_networkports npnp WHERE npnp.networkports_id_1=np.id)) AS idSInterface
        FROM glpi_ipaddresses ip , glpi_networkports np , glpi_computers c
        WHERE ip.items_id=np.id
        AND np.itemtype='Computer'
        AND np.items_id=c.id
        AND ip.version=4
        AND np.mac <> ''
        AND (SELECT npnp.networkports_id_2 from glpi_networkports_networkports npnp WHERE npnp.networkports_id_1=np.id) is not null
        ORDER BY 9 DESC, 1, 3
        LIMIT $eu, $limit";

$result = $DB->query($query);

$class = "tab_bg_2";
while ($data = $DB->fetch_array($result)) {
  echo "<tr class='" . $class . " top'>".
        "<td><a href='". Toolbox::getItemTypeFormURL('Computer') . "?id=" . $data["IDC"]."'>" . $data["UC"] . "</a></td>".
        "<td>". $data["CInterface"] . "</td>".
        "<td>". $data["addrMAC"] . "</td>".
        "<td>". $data["addrIP"] . "</td>".
        "<td><a href='". Toolbox::getItemTypeFormURL('NetworkEquipment') . "?id=" . $data["idSwitch"]."'>" . $data["Switch"] . "</a></td>".
        "<td><a href='". Toolbox::getItemTypeFormURL('NetworkPort') . "?id=" . $data["idSInterface"]."'>" . $data["SInterface"] . "</a></td>".
        "<td class='center'>". Html::convDateTime($data["DateLiaison"]);
    
        if($data["DateLiaison"] != NULL) {
           echo "&nbsp;<i style='color:#1E824C' class='fa fa-check' aria-hidden='true'></i></td>";}
        else {
           echo "<i style='color:#F22613' class='fa fa-close' aria-hidden='true'></i></td>";}

        echo "<td>". $data["EVENT"] . "</td>";
  $class = ($class=="tab_bg_2" ? "tab_bg_1" : "tab_bg_2");
}

echo "</table></div>\n";
    
    $rs1 = $DB->query("SELECT FOUND_ROWS()"); 
    if($rowz = $rs1->fetch_row())
        $nume = $rowz[0];

    if($nume>$limit) {
        echo "<div class='paginate wrapperz'><ul>";
        if($back >=0) { 
            print "<li><a href='$page_name?linked&start=$back'>&lang;</a></li>"; 
        }
        echo "</td><td align=center width='30%'>";
        $i=0;
        $l=1;
        for($i=0;$i < $nume;$i=$i+$limit){
            if($i <> $eu){
                echo "<li><a href='$page_name?linked&start=$i'>$l</a></li>";
            }
            else { echo "<li><a href='' class='active'>$l</a></li>";}
            $l=$l+1;
        }

        echo "</td><td  align='right' width='30%'>";
        if($this1 < $nume) { 
            print "<li><a href='$page_name?linked&start=$next'>&rang;</a></li>";}
        echo "</ul>";
    }

} else if(isset($_GET['failed'])) {
echo "<div class='center'>";
echo "<table class='tab_cadrehov' cellpadding='5'>\n";
echo "<tr class='tab_bg_1 center'>".
      "<th colspan='8'>" . __("État des connexions réseaux (Ordinateurs)") .
      "</th></tr>\n";

echo "<tr class='tab_bg_2'><th>Nom Machine</th>" .
      "<th>Interface Machine</th>".
      "<th>Adresse MAC</th>".
      "<th>Adresse IP</th>".
      "<th>Switch</th>".
      "<th>Interface Switch</th>".
      "<th>Date de liaison</th>".
      "<th>Événement</th></tr>\n";


$query = "SELECT SQL_CALC_FOUND_ROWS c.id AS IDC ,c.name AS UC,  np.name AS CInterface , np.mac AS addrMAC , ip.name AS addrIP
        FROM glpi_ipaddresses ip , glpi_networkports np , glpi_computers c
        WHERE ip.items_id=np.id
        AND np.itemtype='Computer'
        AND np.items_id=c.id
        AND ip.version=4
        AND np.mac <> ''
        AND (SELECT npnp.networkports_id_2 from glpi_networkports_networkports npnp WHERE npnp.networkports_id_1=np.id) is null
        order by 1, 3
        LIMIT $eu, $limit";

$result = $DB->query($query);

$class = "tab_bg_2";
while ($data = $DB->fetch_array($result)) {
  echo "<tr class='" . $class . " top'>".
        "<td><a href='". Toolbox::getItemTypeFormURL('Computer') . "?id=" . $data["IDC"]."'>" . $data["UC"] . "</a></td>".
        "<td>". $data["CInterface"] . "</td>".
        "<td>". $data["addrMAC"] . "</td>".
        "<td>". $data["addrIP"] . "</td>".
        "<td></td>".
        "<td></td>".
        "<td class='center'><i style='color:#F22613' class='fa fa-close' aria-hidden='true'></i></td>".
        "<td></td>";
  $class = ($class=="tab_bg_2" ? "tab_bg_1" : "tab_bg_2");
}

echo "</table></div>\n";
        
    $rs1 = $DB->query("SELECT FOUND_ROWS()"); 
    if($rowz = $rs1->fetch_row())
        $nume = $rowz[0];

    if($nume>$limit) {
        echo "<div class='paginate wrapperz'><ul>";
        if($back >=0) { 
            print "<li><a href='$page_name?failed&start=$back'>&lang;</a></li>"; 
        }
        echo "</td><td align=center width='30%'>";
        $i=0;
        $l=1;
        for($i=0;$i < $nume;$i=$i+$limit){
            if($i <> $eu){
                echo "<li><a href='$page_name?failed&start=$i'>$l</a></li>";
            }
            else { echo "<li><a href='' class='active'>$l</a></li>";}
            $l=$l+1;
        }

        echo "</td><td  align='right' width='30%'>";
        if($this1 < $nume) { 
            print "<li><a href='$page_name?failed&start=$next'>&rang;</a></li>";}
        echo "</ul>";
    }
}

else{ 
    //Afficher tout
    echo "<div class='center'>";
    echo "<table class='tab_cadrehov' cellpadding='5'>\n";
    echo "<tr class='tab_bg_1 center'>".
          "<th colspan='8'>" . __("État des connexions réseaux (Ordinateurs)") .
          "</th></tr>\n";

    echo "<tr class='tab_bg_2'><th>Nom Machine</th>" .
          "<th>Interface Machine</th>".
          "<th>Adresse MAC</th>".
          "<th>Adresse IP</th>".
          "<th>Switch</th>".
          "<th>Interface Switch</th>".
          "<th>Date de liaison</th>".
          "<th>Événement</th></tr>\n";


    $query = "SELECT SQL_CALC_FOUND_ROWS c.id AS IDC ,c.name AS UC,  np.name AS CInterface , np.mac AS addrMAC , ip.name AS addrIP ,
           (SELECT npnp.networkports_id_2 from glpi_networkports_networkports npnp WHERE npnp.networkports_id_1=np.id) AS idInterface,
           (SELECT ne.name FROM glpi_networkequipments ne WHERE ne.id=(SELECT n.items_id FROM glpi_networkports n WHERE n.id=(SELECT npnp.networkports_id_2 from glpi_networkports_networkports npnp WHERE npnp.networkports_id_1=np.id))) AS Switch,
           (SELECT n.name FROM glpi_networkports n WHERE n.id=(SELECT npnp.networkports_id_2 from glpi_networkports_networkports npnp WHERE npnp.networkports_id_1=np.id)) AS SInterface,
           (SELECT e.date FROM glpi_events e WHERE e.type='networkport' and e.items_id=np.id order by 1 desc LIMIT 1)  AS DateLiaison ,
           (SELECT e.message FROM glpi_events e WHERE e.type='networkport' and e.items_id=np.id order by 1 desc LIMIT 1) AS EVENT,
           (SELECT neq.id FROM glpi_networkequipments neq WHERE neq.id = (SELECT n.items_id FROM glpi_networkports n WHERE n.id=(SELECT npnp.networkports_id_2 from glpi_networkports_networkports npnp WHERE npnp.networkports_id_1=np.id))) AS idSwitch,
           (SELECT n.id FROM glpi_networkports n WHERE n.id=(SELECT npnp.networkports_id_2 from glpi_networkports_networkports npnp WHERE npnp.networkports_id_1=np.id)) AS idSInterface
            FROM glpi_ipaddresses ip , glpi_networkports np , glpi_computers c
            WHERE ip.items_id=np.id
            AND np.itemtype='Computer'
            AND np.items_id=c.id
            AND ip.version=4
            AND np.mac <> ''
            order by 9 desc ,1, 3
            LIMIT $eu, $limit";

    $result = $DB->query($query);

    $class = "tab_bg_2";
    while ($data = $DB->fetch_array($result)) {
      echo "<tr class='" . $class . " top'>".
            "<td><a href='". Toolbox::getItemTypeFormURL('Computer') . "?id=" . $data["IDC"]."'>" . $data["UC"] . "</a></td>".
            "<td>". $data["CInterface"] . "</td>".
            "<td>". $data["addrMAC"] . "</td>".
            "<td>". $data["addrIP"] . "</td>".
            "<td><a href='". Toolbox::getItemTypeFormURL('NetworkEquipment') . "?id=" . $data["idSwitch"]."'>" . $data["Switch"] . "</a></td>".
            "<td><a href='". Toolbox::getItemTypeFormURL('NetworkPort') . "?id=" . $data["idSInterface"]."'>" . $data["SInterface"] . "</a></td>".
            "<td class='center'>". Html::convDateTime($data["DateLiaison"]);

            if($data["DateLiaison"] != NULL) {
               echo "&nbsp;<i style='color:#1E824C' class='fa fa-check' aria-hidden='true'></i></td>";}
            else {
               echo "<i style='color:#F22613' class='fa fa-close' aria-hidden='true'></i></td>";}

            echo "<td>". $data["EVENT"] . "</td>";
      $class = ($class=="tab_bg_2" ? "tab_bg_1" : "tab_bg_2");
    }

    echo "</table></div>\n";
    
    $rs1 = $DB->query("SELECT FOUND_ROWS()"); 
    if($rowz = $rs1->fetch_row())
        $nume = $rowz[0];

    if($nume>$limit) {
        echo "<div class='paginate wrapperz'><ul>";
        if($back >=0) { 
            print "<li><a href='$page_name?start=$back'>&lang;</a></li>"; 
        }
        echo "</td><td align=center width='30%'>";
        $i=0;
        $l=1;
        for($i=0;$i < $nume;$i=$i+$limit){
            if($i <> $eu){
                echo "<li><a href='$page_name?start=$i'>$l</a></li>";
            }
            else { echo "<li><a href='' class='active'>$l</a></li>";}
            $l=$l+1;
        }

        echo "</td><td  align='right' width='30%'>";
        if($this1 < $nume) { 
            print "<li><a href='$page_name?start=$next'>&rang;</a></li>";}
        echo "</ul>";
    }
}



Html::footer();
?>