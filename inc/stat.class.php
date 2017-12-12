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


class PluginWilayaStat extends CommonGLPI {
        
    static function displayAllStats($type = "default") {
        global $CFG_GLPI;
            
        echo "<div class='col-md-4'>".
                "<div class='info-box bg-yellow'>".
                 "<span class='info-box-icon'><i class='ion ion-ios-pricetag-outline'></i></span>".
                 "<div class='info-box-content'>".
                    "<span class='info-box-text'>Inventory</span>".
                    "<span class='info-box-number'>5,200</span>".
                    "<div class='progress'>".
                        "<div class='progress-bar' style='width: 50%'></div>".
                    "</div>".
                    "<span class='progress-description'>".
                        "50% Increase in 30 Days".
                    "</span>".
                 "</div>".
                "</div>".
              "</div>";
    }
}

?>