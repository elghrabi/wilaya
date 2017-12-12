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


class PluginWilayaMenu extends CommonGLPI {
    
    static function displayMenu($type = "default") {
        global $CFG_GLPI;
        include_once("configuration.php");
        
         echo "<center>";
         echo "<a href='".$CFG_GLPI['root_doc']."/plugins/wilaya/front/index.php'>";
         echo "<img src='".$CFG_GLPI['root_doc']."/plugins/wilaya/pics/logo.png'/></a>";
         echo "<h2>Version ". WILAYA_VERSION ."</h2><br>";
         echo "<ul id=\"wilayaItems\">";
         echo "<li class=\"wil-item\"><a href=".$CFG_GLPI['root_doc']."/plugins/wilaya/front/index.php><i class=\"fa fa-home fa-2x\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;Accueil</a></li>";
         echo "<li class=\"wil-item\"><a href=".$CFG_GLPI['root_doc']."/plugins/wilaya/front/usertrace.php><i class=\"fa fa-street-view fa-2x\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;Suivi des utilisateurs</a></li>";
         echo "<li class=\"wil-item\"><a href=".$CFG_GLPI['root_doc']."/plugins/wilaya/front/rapports/etatreseaux.php><i class=\"fa fa-wpforms fa-2x\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;Rapports</a></li>";
         echo "<li class=\"wil-item\"><a href=".$CFG_GLPI['root_doc']."/plugins/wilaya/front/about.php><i class=\"fa fa-info-circle fa-2x\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;À propos</a></li>";
         echo "</ul><br><br><hr>";
         echo "</center>";
    }
}

?>