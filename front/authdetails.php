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

Html::header("Details d'authentification - Wilaya", $_SERVER['PHP_SELF'],"plugins");
PluginWilayaMenu::displayMenu();

$USEDBREPLICATE         = 1;
$DBCONNECTION_REQUIRED  = 0;

if(isset($_GET['dateAuth'])) {
    $date_auth = $_GET['dateAuth'];
    echo "<div class='center'>";
    echo "<table class='tab_cadrehov' cellpadding='3'>\n";
    echo "<tr class='tab_bg_1 center'>".
      "<th colspan='3'>" . __("Total des authentifications ($date_auth)") .
      "</th></tr>\n";
    
    echo "<tr class='tab_bg_2'>".
         "<th>Utilisateur</th>" .
         "<th>Adresse IP</th>".
         "<th>Heure</th>";
  if(isset($_GET['usr'])) {
    $id_user = $_GET['usr'];
    
    $query = "SELECT usr.firstname AS Prenom, usr.realname AS Nom, usr.name AS Username
         FROM glpi_users usr
         WHERE usr.id = $id_user";
    
    $result = $DB->query($query);

    $class = "tab_bg_2";
    while ($data = $DB->fetch_array($result)) {
          $quer = "SELECT DATE_FORMAT(ev.date,'%H:%i:%s') AS EVTime, SUBSTRING_INDEX(ev.message, ' ', -1) AS IPAddr
         FROM glpi_events ev
         WHERE ev.service = 'login'
         AND ev.level = 3
         AND DATE_FORMAT(ev.date,'%Y-%m-%d') = STR_TO_DATE('$date_auth','%Y-%m-%d')
         AND ev.message LIKE CONCAT('".$data["Username"]."', '%')
         ORDER BY EVTime DESC";
    
        $resultats = $DB->query($quer);
        while($dataz = $DB->fetch_array($resultats))
        {            
          echo "<tr class='" . $class . " top'>";
          if($data["Prenom"] == NULL || $data["Nom"] == NULL) {
             echo "<td class='center'><a href='". Toolbox::getItemTypeFormURL('User')."?id=".$id_user."'/>".$data["Username"]."</a></td>";
          } else {
             echo "<td class='center'><a href='". Toolbox::getItemTypeFormURL('User')."?id=".$id_user."'/>".$data["Prenom"]." ".$data["Nom"]."</a></td>";
          }
           echo "<td class='center'><i class='fa fa-globe' aria-hidden='true'></i>&nbsp;<span class='label label-light label-rounded'>". $dataz["IPAddr"] ."</span></td>".
                "<td class='center'><i class='fa fa-clock-o' aria-hidden='true'></i>&nbsp;". $dataz["EVTime"] ."</td>";
        }
      $class = ($class=="tab_bg_2" ? "tab_bg_1" : "tab_bg_2");
    }
    echo "</table></div>\n";
  } else {    
    $query = "SELECT usr.firstname AS Prenom, usr.realname AS Nom, usr.name AS Username, DATE_FORMAT(ev.date,'%H:%i:%s') AS EVTime, SUBSTRING_INDEX(ev.message, ' ', -1) AS IPAddr
         FROM glpi_events ev, glpi_users usr
         WHERE ev.service = 'login'
         AND ev.level = 3
         AND DATE_FORMAT(ev.date,'%Y-%m-%d') = STR_TO_DATE('$date_auth','%Y-%m-%d')
         AND ev.message LIKE CONCAT(usr.name, '%')
         ORDER BY EVTime DESC";
    $result = $DB->query($query);

    $class = "tab_bg_2";
    while ($data = $DB->fetch_array($result)) {      
          echo "<tr class='" . $class . " top'>";
          if($data["Prenom"] == NULL || $data["Nom"] == NULL) {
             echo "<td class='center'><a href='". Toolbox::getItemTypeFormURL('User')."?id=".$id_user."'/>".$data["Username"]."</a></td>";
          } else {
             echo "<td class='center'><a href='". Toolbox::getItemTypeFormURL('User')."?id=".$id_user."'/>".$data["Prenom"]." ".$data["Nom"]."</a></td>";
          }
           echo "<td class='center'><i class='fa fa-globe' aria-hidden='true'></i>&nbsp;<span class='label label-light label-rounded'>". $data["IPAddr"] ."</span></td>".
                "<td class='center'><i class='fa fa-clock-o' aria-hidden='true'></i>&nbsp;". $data["EVTime"] ."</td>";
      $class = ($class=="tab_bg_2" ? "tab_bg_1" : "tab_bg_2");
    }
    echo "</table></div>\n";
  }
} else if(isset($_GET['date1'])  && isset($_GET['date2'])) {
    $date1 = $_GET['date1'];
    $date2 = $_GET['date2'];
    
    echo "<div class='center'>";
        echo "<table class='tab_cadrehov' cellpadding='3'>\n";
        echo "<tr class='tab_bg_1 center'>".
          "<th colspan='4'>" . __("Total des authentifications ($date1 - $date2)") .
          "</th></tr>\n";

        echo "<tr class='tab_bg_2'>".
             "<th>Utilisateur</th>" .
             "<th>Adresse IP</th>".
             "<th>Date</th>".
             "<th>Heure</th>";
    
    if(isset($_GET['usr'])) {
        $id_user = $_GET['usr'];
        $query = "SELECT usr.firstname AS Prenom, usr.realname AS Nom, usr.name AS Username
             FROM glpi_users usr
             WHERE usr.id = $id_user";

        $result = $DB->query($query);

        $class = "tab_bg_2";
        while ($data = $DB->fetch_array($result)) {
              $quer = "SELECT DATE_FORMAT(ev.date,'%d-%m-%Y') AS EVDate, DATE_FORMAT(ev.date,'%H:%i:%s') AS EVTime, SUBSTRING_INDEX(ev.message, ' ', -1) AS IPAddr
             FROM glpi_events ev
             WHERE ev.service = 'login'
             AND ev.level = 3
             AND DATE_FORMAT(ev.date,'%Y-%m-%d') BETWEEN STR_TO_DATE('$date2','%Y-%m-%d') AND STR_TO_DATE('$date1','%Y-%m-%d')
             AND ev.message LIKE CONCAT('".$data["Username"]."', '%')
             ORDER BY EVTime DESC";

            $resultats = $DB->query($quer);
            while($dataz = $DB->fetch_array($resultats))
            {            
              echo "<tr class='" . $class . " top'>";
              if($data["Prenom"] == NULL || $data["Nom"] == NULL) {
                 echo "<td class='center'><a href='". Toolbox::getItemTypeFormURL('User')."?id=".$id_user."'/>".$data["Username"]."</a></td>";
              } else {
                 echo "<td class='center'><a href='". Toolbox::getItemTypeFormURL('User')."?id=".$id_user."'/>".$data["Prenom"]." ".$data["Nom"]."</a></td>";
              }
               echo "<td class='center'><i class='fa fa-globe' aria-hidden='true'></i>&nbsp;<span class='label label-light label-rounded'>". $dataz["IPAddr"] ."</span></td>".
                    "<td class='center'><i class='fa fa-calendar-o' aria-hidden='true'></i>&nbsp;". $dataz["EVDate"] ."</td>".
                   "<td class='center'><i class='fa fa-clock-o' aria-hidden='true'></i>&nbsp;". $dataz["EVTime"] ."</td>";
            }
          $class = ($class=="tab_bg_2" ? "tab_bg_1" : "tab_bg_2");
        }
        echo "</table></div>\n";
    } else {
        $query = "SELECT usr.firstname AS Prenom, usr.realname AS Nom, usr.name AS Username, DATE_FORMAT(ev.date,'%d-%m-%Y') AS EVDate, DATE_FORMAT(ev.date,'%H:%i:%s') AS EVTime, SUBSTRING_INDEX(ev.message, ' ', -1) AS IPAddr
         FROM glpi_events ev, glpi_users usr
         WHERE ev.service = 'login'
         AND ev.level = 3
         AND DATE_FORMAT(ev.date,'%Y-%m-%d') BETWEEN STR_TO_DATE('$date2','%Y-%m-%d') AND STR_TO_DATE('$date1','%Y-%m-%d')
         AND ev.message LIKE CONCAT(usr.name, '%')
         ORDER BY EVTime DESC";
        $result = $DB->query($query);

        $class = "tab_bg_2";
        while ($data = $DB->fetch_array($result)) {      
              echo "<tr class='" . $class . " top'>";
              if($data["Prenom"] == NULL || $data["Nom"] == NULL) {
                 echo "<td class='center'><a href='". Toolbox::getItemTypeFormURL('User')."?id=".$id_user."'/>".$data["Username"]."</a></td>";
              } else {
                 echo "<td class='center'><a href='". Toolbox::getItemTypeFormURL('User')."?id=".$id_user."'/>".$data["Prenom"]." ".$data["Nom"]."</a></td>";
              }
               echo "<td class='center'><i class='fa fa-globe' aria-hidden='true'></i>&nbsp;<span class='label label-light label-rounded'>". $data["IPAddr"] ."</span></td>".
                   "<td class='center'><i class='fa fa-calendar-o' aria-hidden='true'></i>&nbsp;". $data["EVDate"] ."</td>".
                    "<td class='center'><i class='fa fa-clock-o' aria-hidden='true'></i>&nbsp;". $data["EVTime"] ."</td>";
          $class = ($class=="tab_bg_2" ? "tab_bg_1" : "tab_bg_2");
        }
        echo "</table></div>\n";
    }
} else {
    header("Location: usertrace.php");
}

Html::footer();
?>