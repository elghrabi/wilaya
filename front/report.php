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


include ("../../../inc/includes.php");
include ("../../../config/config.php");

//Session::checkRight("config", "w");

Html::header($_GET['rapport_titre'] . " - Wilaya", $_SERVER['PHP_SELF'],"plugins");
PluginWilayaMenu::displayMenu();
PluginWilayaReport::displaySubMenu();

$USEDBREPLICATE         = 1;
$DBCONNECTION_REQUIRED  = 0;

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


$query = "SELECT c.id AS IDC ,c.name AS UC,  np.name AS CInterface , np.mac AS addrMAC , ip.name AS addrIP ,
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
        order by 9 desc ,1, 3";

$result = $DB->query($query);

$prev = "";
$class = "tab_bg_2";
while ($data = $DB->fetch_array($result)) {
   if ($prev == $data["UC"].$data["DateLiaison"]) {
      echo "<br />";
   } else {
      if (!empty($prev)) {
         echo "</td></tr>\n";
      }
      $prev = $data["UC"].$data["DateLiaison"];
      echo "<tr class='" . $class . " top'>".
            "<td><a href='". Toolbox::getItemTypeFormURL('Computer') . "?id=" . $data["IDC"]."'>" . $data["UC"] . "</a></td>".
            "<td>". $data["CInterface"] . "</td>".
            "<td>". $data["addrMAC"] . "</td>".
            "<td>". $data["addrIP"] . "</td>".
            "<td><a href='". Toolbox::getItemTypeFormURL('NetworkEquipment') . "?id=" . $data["idSwitch"]."'>" . $data["Switch"] . "</a></td>".
            "<td><a href='". Toolbox::getItemTypeFormURL('NetworkPort') . "?id=" . $data["idSInterface"]."'>" . $data["SInterface"] . "</a></td>".
            "<td class='center'>". Html::convDateTime($data["DateLiaison"]) . "</td>" .
            "<td>". $data["EVENT"] . "</td>".
            "<td>";
      $class = ($class=="tab_bg_2" ? "tab_bg_1" : "tab_bg_2");
   }
}

if (!empty($prev)) {
   echo "</td></tr>\n";
}
echo "</table></div>\n";

Html::footer();
?>