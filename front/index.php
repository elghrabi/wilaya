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

Html::header("Tableau de bord Wilaya", $_SERVER['PHP_SELF'],"plugins");
PluginWilayaMenu::displayMenu();

$todayAuth = 0; $weekAuth = 0; $totalAuth = 0; $repTotal = 0; 

$query = "SELECT 
        (SELECT COUNT(ev.id)
        FROM glpi_events ev
        WHERE DATE_FORMAT(ev.date,'%Y-%m-%d') = STR_TO_DATE(CURDATE(),'%Y-%m-%d')
        AND ev.service = 'login'
        AND ev.level = 3) AS AuthToday,
        (SELECT COUNT(ev.id)
        FROM glpi_events ev
        WHERE DATE_FORMAT(ev.date,'%Y-%m-%d') BETWEEN STR_TO_DATE(CURDATE()- INTERVAL 6 DAY,'%Y-%m-%d') AND STR_TO_DATE(CURDATE(),'%Y-%m-%d') 
        AND ev.service = 'login'
        AND ev.level = 3) AS AuthWeek,
        (SELECT COUNT(ev.id)
        FROM glpi_events ev
        WHERE ev.service = 'login'
        AND ev.level = 3) AS AuthTotal,
        (SELECT COUNT(wil.id)
        FROM glpi_plugin_wilaya_reports wil) AS RepTotal";

$result = $DB->query($query);

while ($data = $DB->fetch_array($result)) {
    $todayAuth = $data["AuthToday"];
    $weekAuth = $data["AuthWeek"];
    $totalAuth = $data["AuthTotal"];
    $repTotal = $data["RepTotal"];
}
?>

<br>
<div class="containerw">
    <div class="roww">
        <div class="col-md-8">
            <div class="box box-wil">
                <div class="box-header with-border">
                  <h3 class="box-title">Statistiques utilisateurs <small>(Nombre d'authentifications)</small></h3>
                </div>
                <div class="box-body">
                    <canvas id="pieChart" style="height:250px"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-3">
          <div class="info-box bg-yellow">
            <a href="authdetails.php<?php echo "?dateAuth=".getFullDate(0,'Y-m-d'); ?>"><span class="info-box-icon"><i class="fa fa-calendar-check-o" aria-hidden="true"></i></span></a>
            <div class="info-box-content">
              <span class="info-box-text">Authentifications</span>
              <span class="progress-description">
                Aujourd'hui
              </span>
              <span class="info-box-number countr"><?php echo $todayAuth; ?></span>
            </div>
          </div>
          <div class="info-box bg-green">
            <a href="authdetails.php<?php echo "?date1=".getFullDate(0,'Y-m-d')."&date2=". getFullDate(6,'Y-m-d') ?>"><span class="info-box-icon"><i class="fa fa-calendar" aria-hidden="true"></i></span></a>
            <div class="info-box-content">
              <span class="info-box-text">Authentifications</span>
              <span class="progress-description">
                Cette semaine
              </span>
              <span class="info-box-number countr"><?php echo $weekAuth; ?></span>
            </div>
          </div>
          <div class="info-box bg-red">
            <span class="info-box-icon"><i class="fa fa-users" aria-hidden="true"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Authentifications</span>
              <span class="progress-description">
                Total
              </span>
              <span class="info-box-number countr"><?php echo $totalAuth; ?></span>
            </div>
          </div>
          <div class="info-box bg-aqua">
            <a href="rapports.php"><span class="info-box-icon"><i class="fa fa-wpforms" aria-hidden="true"></i></span></a>
            <div class="info-box-content">
              <span class="info-box-text">Rapports</span>
              <span class="info-box-number countr"><?php echo $repTotal; ?></span>
            </div>
          </div>
        </div>
    </div>
    <div class="roww">
        <div class="col-md-8">
            <div class="table-events">
                <div class="row-event header-ev blue">
                  <div class="cell-ev">
                    Derniers évènements
                  </div>
        
                  <div class="cell-ev">
                    
                  </div>
                  <div class="cell-ev">
                    
                  </div>
                </div>
                <div class="row-event header-ev blue">
                  <div class="cell-ev">
                    Évènement
                  </div>
        
                  <div class="cell-ev">
                    Date
                  </div>
                  <div class="cell-ev">
                    Type
                  </div>
                </div> 
                
            <?php
                $last_events = "SELECT usr.id AS IDuser, usr.name AS NAMEuser, ev.message, ev.level, DATE_FORMAT(ev.date,'%d-%m-%Y') AS date FROM glpi_events ev, glpi_users usr WHERE DATE(ev.date) <= CURDATE() AND DATE(ev.date) >= CURDATE() - INTERVAL 3 DAY AND ev.message LIKE CONCAT(usr.name, '%')
                ORDER BY date DESC";
                $lastevRes = $DB->query($last_events);  
                    
                while($evenements = $DB->fetch_array($lastevRes)) {
            ?>
                <div class="row-event">
                  <div class="cell-ev">
                      <?php
                        $user_ev = strtok($evenements["message"], " ");
                        $ev_msg = substr(strstr($evenements["message"]," "), 1);
                        echo "<a href='". Toolbox::getItemTypeFormURL('User')."?id=".$evenements["IDuser"]."'/>".$user_ev."</a>" . " " . $ev_msg;
                      ?>
                  </div>
        
                  <div class="cell-ev">
                    <?php echo $evenements["date"]; ?>
                  </div>
                    
                  <div class="cell-ev">
                      
                    <?php
                        if($evenements["level"] == 3 || $evenements["level"] == 1)
                        echo "<span class='label label-yellow'>Authentification</span>";
                        else if($evenements["level"] == 4 || $evenements["level"] == 5)
                        echo "<span class='label label-success'>Mise à jour</span>";
                        else echo "Autre";
                    ?>
                          
                  </div>
                    </div> 
            <?php
                }
            ?>
                
            </div>
        </div>
    </div>
</div>

<?php
 $valid_q = "SELECT usr.firstname AS Prenom, usr.realname AS Nom, usr.name AS Username, COUNT(ev.id) AS TotalAuth
         FROM glpi_events ev, glpi_users usr
         WHERE ev.service = 'login'
         AND ev.level = 3
         AND ev.message LIKE CONCAT(usr.name, '%')
         GROUP BY usr.name
         ORDER BY COUNT(ev.id) DESC
         LIMIT 6";

 $result = $DB->query($valid_q);
 $colors_array = array("#f56954","#00a65a","#f39c12","#00c0ef","#3c8dbc","#d2d6de","#EC644B","#CF000F","#D2527F","#DB0A5B","#663399","#674172","#BF55EC","#913D88","#AEA8D3","#446CB3","#81CFE0","#336E7B","#67809F","#4ECDC4");
?>

<script src="../js/jQuery-2.1.4.min.js"></script>
<script src="../js/Chart.min.js"></script>
<script type="text/javascript">
    $('.countr').each(function () {
        $(this).prop('Counter',0).animate({
            Counter: $(this).text()
        }, {
            duration: 4000,
            easing: 'swing',
            step: function (now) {
                $(this).text(Math.ceil(now));
            }
        });
    });
    
    
    var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
    var pieChart = new Chart(pieChartCanvas);
    var PieData = [
    <?php while($membres = $DB->fetch_array($result)) {
    ?>
      {
        value: '<?php echo (int)$membres["TotalAuth"]; ?>',
        color: "<?php $clr_q = $colors_array[array_rand($colors_array)]; echo $clr_q ?>",
        highlight: "<?php echo $clr_q; ?>",
        label: "<?php
            if($membres["Prenom"] == NULL || $membres["Nom"] == NULL)
                echo $membres["Username"];
            else
                echo $membres["Prenom"] . " " . $membres["Nom"];
          ?>"
      },      
    <?php  } ?>
    ];
    var pieOptions = {
      segmentShowStroke: true,
      segmentStrokeColor: "#fff",
      segmentStrokeWidth: 2,
      percentageInnerCutout: 50,
      animationSteps: 100,
      animationEasing: "easeOutBounce",
      animateRotate: true,
      animateScale: false,
      responsive: true,
      maintainAspectRatio: true,
      legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
    };

    pieChart.Doughnut(PieData, pieOptions);
</script>

<?php
Html::footer();
?>