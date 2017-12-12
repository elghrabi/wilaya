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
include("../inc/functions.php");

//Session::checkRight("config", "w");

Html::header("Suivi des utilisateurs - Wilaya", $_SERVER['PHP_SELF'],"plugins");
PluginWilayaMenu::displayMenu();

$USEDBREPLICATE         = 1;
$DBCONNECTION_REQUIRED  = 0;

$page_name="usertrace.php"; //  If you use this code with a different page ( or file ) name then change this 
$start=$_GET['start'];

if(strlen($start) > 0 and !is_numeric($start)){
    echo "Erreur de données";
    exit;
}

$eu = ($start - 0);
$limit = 10;
$this1 = $eu + $limit;
$back = $eu - $limit;
$next = $eu + $limit;

echo "<div class='center'>";
echo "<table class='tab_cadrehov' cellpadding='5'>\n";
echo "<tr class='tab_bg_1 center'>".
      "<th colspan='10'>" . __("Suivi des utilisateurs (Authentifications)") .
      "</th></tr>\n";


echo "<tr class='tab_bg_2'><th>Nom d'utilisateur</th>" .
      "<th>Profiles</th>".
      "<th class='flatcol-lvl'>Aujourd'hui<br>(" . getFullDate(0,'j/m/y') .")</th>".
      "<th class='flatcol-lvl'>Hier<br>(".getFullDate(1,'j/m/y').")</th>".
      "<th class='flatcol-lvl'>".getFullDate(2,'j/m/y')."</th>".
      "<th class='flatcol-lvl'>".getFullDate(3,'j/m/y')."</th>".
      "<th class='flatcol-lvl'>".getFullDate(4,'j/m/y')."</th>".
      "<th class='flatcol-lvl'>".getFullDate(5,'j/m/y')."</th>".
      "<th class='flatcol-lvl'>".getFullDate(6,'j/m/y')."</th>".
      "<th>Total</th>";

$query = "SELECT SQL_CALC_FOUND_ROWS usr.id AS IdUser, usr.firstname AS Prenom, usr.realname AS Nom, usr.name AS Username, usr.picture AS Avatar,
         (SELECT COUNT(ev.id)
         FROM glpi_events ev
         WHERE ev.service = 'login'
         AND ev.level = 3
         AND DATE(ev.date) = CURDATE()
         AND ev.message LIKE CONCAT(usr.name, '%')
         ) AS Today,
         (SELECT COUNT(ev.id)
         FROM glpi_events ev
         WHERE ev.service = 'login'
         AND ev.level = 3
         AND DATE(ev.date) = CURDATE() - INTERVAL 1 DAY
         AND ev.message LIKE CONCAT(usr.name, '%')
         ) AS Yesterday,
         (SELECT COUNT(ev.id)
         FROM glpi_events ev
         WHERE ev.service = 'login'
         AND ev.level = 3
         AND DATE(ev.date) = CURDATE() - INTERVAL 2 DAY
         AND ev.message LIKE CONCAT(usr.name, '%')
         ) AS ThirdDay,
         (SELECT COUNT(ev.id)
         FROM glpi_events ev
         WHERE ev.service = 'login'
         AND ev.level = 3
         AND DATE(ev.date) = CURDATE() - INTERVAL 3 DAY
         AND ev.message LIKE CONCAT(usr.name, '%')
         ) AS FourthDay,
         (SELECT COUNT(ev.id)
         FROM glpi_events ev
         WHERE ev.service = 'login'
         AND ev.level = 3
         AND DATE(ev.date) = CURDATE() - INTERVAL 4 DAY
         AND ev.message LIKE CONCAT(usr.name, '%')
         ) AS FifthDay,
         (SELECT COUNT(ev.id)
         FROM glpi_events ev
         WHERE ev.service = 'login'
         AND ev.level = 3
         AND DATE(ev.date) = CURDATE() - INTERVAL 5 DAY
         AND ev.message LIKE CONCAT(usr.name, '%')
         ) AS SixthDay,
         (SELECT COUNT(ev.id)
         FROM glpi_events ev
         WHERE ev.service = 'login'
         AND ev.level = 3
         AND DATE(ev.date) = CURDATE()- INTERVAL 6 DAY
         AND ev.message LIKE CONCAT(usr.name, '%')
         ) AS SeventhDay
         FROM glpi_users usr
         ORDER BY Today DESC
         LIMIT $eu, $limit";

    $result = $DB->query($query);

    $class = "tab_bg_2";
    while ($data = $DB->fetch_array($result)) {
    $quer = "SELECT usr.id AS IdUser, usr.name AS NomUser, pr.id AS IdProfile, pr.name AS Profile
              FROM glpi_users usr, glpi_profiles pr, glpi_profiles_users pru
              WHERE pru.users_id = usr.id AND pru.profiles_id = pr.id";
    
    $resultats = $DB->query($quer);
        
    echo "<tr class='" . $class . " top'>";
        if($data["Avatar"] == null) {
            if($data["Prenom"] == NULL || $data["Nom"] == NULL) {
                echo "<td><a href='". Toolbox::getItemTypeFormURL('User')."?id=".$data["IdUser"]."'/>".$data["Username"]."<img class='brd-img' src='". $CFG_GLPI['root_doc'] ."/plugins/wilaya/pics/avatar.png" ."' alt='". $data["IdUser"] ."'/></a></td>";
            }
            else
            {
                echo "<td><a href='". Toolbox::getItemTypeFormURL('User')."?id=".$data["IdUser"]."'/>".$data["Prenom"]." ".$data["Nom"]."<img class='brd-img' src='". $CFG_GLPI['root_doc'] ."/plugins/wilaya/pics/avatar.png" ."'/></a></td>";
            }
        } else {
            if($data["Prenom"] == NULL || $data["Nom"] == NULL) {
                echo "<td><a href='". Toolbox::getItemTypeFormURL('User')."?id=".$data["IdUser"]."'/><img class='brd-img' width='48px' height='48px' src='". $CFG_GLPI['root_doc'] ."/front/document.send.php?file=_pictures/".$data["Avatar"]."' alt='".$data["IdUser"]."'/>".$data["Username"]."</a></td>";
            }
            else
            {
                echo "<td><a href='". Toolbox::getItemTypeFormURL('User')."?id=".$data["IdUser"]."'/><img class='brd-img' width='48px' height='48px' src='". $CFG_GLPI['root_doc'] ."/front/document.send.php?file=_pictures/".$data["Avatar"]."' alt='".$data["Prenom"]." ".$data["Nom"]."'/>".$data["Prenom"]." ".$data["Nom"]."</a></td>";
            }
        }
        
       echo "<td class='center'><ul>";
        while($dataz = $DB->fetch_array($resultats)) 
        {
          if($dataz['IdUser'] == $data['IdUser']) {
            if($dataz['IdProfile'] == 1)
                echo "<li style='padding: 1px'><a href='". Toolbox::getItemTypeFormURL('Profile') . "?id=" . $dataz['IdProfile'] ."'><span class='badge badge-light'>". $dataz['Profile'] ."</span></a></li>";
            else if($dataz['IdProfile'] == 2)
                echo "<li style='padding: 1px'><a href='". Toolbox::getItemTypeFormURL('Profile') . "?id=" . $dataz['IdProfile'] ."'><span class='badge badge-info'>". $dataz['Profile'] ."</span></a></li>";
            else if($dataz['IdProfile'] == 3)
                echo "<li style='padding: 1px'><a href='". Toolbox::getItemTypeFormURL('Profile') . "?id=" . $dataz['IdProfile'] ."'><span class='badge badge-important'>". $dataz['Profile'] ."</span></a></li>";
          else if($dataz['IdProfile'] == 4)
                echo "<li style='padding: 1px'><a href='". Toolbox::getItemTypeFormURL('Profile') . "?id=" . $dataz['IdProfile'] ."'><span class='badge badge-success'>". $dataz['Profile'] ."</span></a></li>";
          else if($dataz['IdProfile'] == 5)
                echo "<li style='padding: 1px'><a href='". Toolbox::getItemTypeFormURL('Profile') . "?id=" . $dataz['IdProfile'] ."'><span class='badge badge-purple'>". $dataz['Profile'] ."</span></a></li>";
          else if($dataz['IdProfile'] == 6)
                echo "<li style='padding: 1px'><a href='". Toolbox::getItemTypeFormURL('Profile') . "?id=" . $dataz['IdProfile'] ."'><span class='badge badge-yellow'>". $dataz['Profile'] ."</span></a></li>";
          else if($dataz['IdProfile'] == 7)
                echo "<li style='padding: 1px'><a href='". Toolbox::getItemTypeFormURL('Profile') . "?id=" . $dataz['IdProfile'] ."'><span class='badge badge-grey'>". $dataz['Profile'] ."</span></a></li>";
          }  
        }
    
      $totalweek = $data["Today"]+$data["Yesterday"]+$data["ThirdDay"]+$data["FourthDay"]+$data["FifthDay"]+$data["SixthDay"]+$data["SeventhDay"];
    
       echo "</ul></td>";
       echo "<td class='center'><a href='authdetails.php?usr=".$data["IdUser"]."&dateAuth=". getFullDate(0,'Y-m-d') ."'>". $data["Today"] . "</a></td>".
        "<td class='center'><a href='authdetails.php?usr=". $data["IdUser"] ."&dateAuth=". getFullDate(1,'Y-m-d') ."'>". $data["Yesterday"] ."</a></td>".
        "<td class='center'><a href='authdetails.php?usr=". $data["IdUser"] ."&dateAuth=". getFullDate(2,'Y-m-d') ."'>". $data["ThirdDay"] ."</a></td>".
        "<td class='center'><a href='authdetails.php?usr=". $data["IdUser"] ."&dateAuth=". getFullDate(3,'Y-m-d') ."'>". $data["FourthDay"] ."</a></td>".
        "<td class='center'><a href='authdetails.php?usr=". $data["IdUser"] ."&dateAuth=". getFullDate(4,'Y-m-d') ."'>". $data["FifthDay"] ."</a></td>".
        "<td class='center'><a href='authdetails.php?usr=". $data["IdUser"] ."&dateAuth=". getFullDate(5,'Y-m-d') ."'>". $data["SixthDay"] ."</a></td>".
        "<td class='center'><a href='authdetails.php?usr=". $data["IdUser"] ."&dateAuth=". getFullDate(6,'Y-m-d') ."'>". $data["SeventhDay"] ."</a></td>".
        "<td class='center badge'><a href='authdetails.php?usr=". $data["IdUser"] ."&date1=". getFullDate(0,'Y-m-d') . "&date2=". getFullDate(6,'Y-m-d') ."'>". $totalweek ."</td>";
  $class = ($class=="tab_bg_2" ? "tab_bg_1" : "tab_bg_2");
}
        echo "<tr class='" . $class . " top'>";
        echo "<td></td>".
             "<td></td>".
             "<td class='center'></td>".
             "<td class='center'></td>".
             "<td class='center'></td>".
             "<td class='center'></td>".
             "<td class='center'></td>".
             "<td class='center'></td>".
             "<td class='center'></td>".
             "<td class='center'></td>";
echo "</table></div>";

$rs1 = $DB->query("SELECT FOUND_ROWS()"); 
    if($rowz = $rs1->fetch_row())
        $nume = $rowz[0];

    if($nume>$limit) {
        echo "<div class='paginate wrapperz'><ul>";
        if($back >=0) { 
            print "<li><a href='$page_name?start=$back'>&lang;</a></li>"; 
        }
        $i=0;
        $l=1;
        for($i=0;$i < $nume;$i=$i+$limit){
            if($i <> $eu){
                echo "<li><a href='$page_name?start=$i'>$l</a></li>";
            }
            else { echo "<li><a href='' class='active'>$l</a></li>";}
            $l=$l+1;
        }

        if($this1 < $nume) { 
            print "<li><a href='$page_name?start=$next'>&rang;</a></li>";
        }
        echo "</ul>";
    }

Html::footer();
?>