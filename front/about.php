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

Html::header("À propos de Wilaya", $_SERVER['PHP_SELF'],"plugins");
PluginWilayaMenu::displayMenu();
?>
<div class="containerw">
   <div class="roww">
       <div class="col-md-6">
        
       </div>
        <div class="col-md-6">
            <h2>À propos</h2>
            <p>Ce plugin a été créé dans le cadre d'un stage à la préfecture d'Agadir Ida-Outanane</p>
            <h4>Version: 0.1.0</h4>
            <h4>Repo: <a target="_blank" href="https://github.com/elghrabi">github.com/elghrabi</a>
            <br>Contact: <a href="mailto:elghrabisoufiane@gmail.com">elghrabisoufiane@gmail.com</a></h4>
       </div>

    </div>
</div>
<?php
Html::footer();
?>