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


class PluginWilayaConfig extends CommonDBTM {
    
    function getConfiguration() {
        global $DB;

        $query = "SELECT * FROM glpi_plugin_wilaya_config WHERE vie='1'";
        if ($result = $DB->query($query)) {
            if ($DB->numrows($result) > 0) {
                $i = 0;
                while ($row = $DB->fetch_assoc($result)) {
                  if (!empty($row['id'])) { 
                      $config['id'] = $row['id'];
                  }
                  else {
                      $config['id'] = "";
                  }
                  if (!empty($row['statut'])) {
                      $config['statut'] = $row['statut'];
                  }
                  else {
                      $config['statut'] = "";
                   }
                    
                  $retour[$i] = $config;
                  $i++;
                }
            }  
        }
        return $retour;
    }

}


?>