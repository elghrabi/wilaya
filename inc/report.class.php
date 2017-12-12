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


class PluginWilayaReport extends CommonGLPI {
        
    static function displaySubMenu($type = "default") {
        global $CFG_GLPI;
            
         echo "<br><center>";
         echo "<h3>Liste des rapports disponibles</h3>";
         echo "<ul id=\"wilayaReports\">";
         echo "<li class=\"rapportItem\"><a href=".$CFG_GLPI['root_doc']."/plugins/wilaya/front/rapports/etatreseaux.php>Rapport: État des connexions réseaux&nbsp;&nbsp;&nbsp;<i class=\"fa fa-tag\" aria-hidden=\"true\"></i></a></li>";

         echo "</ul><br><br>";
         echo "</center>";
    }
}

?>