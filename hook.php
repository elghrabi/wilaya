<?php  
/* Nom du fichier: hook.php
   Auteur: Soufiane
*/

function plugin_wilaya_install() {
   global $DB, $LANG;
    
    if (!TableExists("glpi_plugin_wilaya_config")) {
        $query = "CREATE TABLE `glpi_plugin_wilaya_config` (
                  `id` int(4) NOT NULL AUTO_INCREMENT,
                  `name` varchar(50) NOT NULL,
                  `value` varchar(25) NOT NULL,
                  `users_id` varchar(25) NOT NULL DEFAULT '',
                  PRIMARY KEY (`id`,`name`,`value`,`users_id`),
                  UNIQUE KEY `name` (`name`,`users_id`),
                  KEY `name_2` (`name`,`users_id`)) 
                  ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";
        $DB->query($query) or die("Erreur lors de la création de la table glpi_plugin_wilaya_config " . $DB->error());
    }  
    
    if (!TableExists("glpi_plugin_wilaya_reports")) {
        $query = "CREATE TABLE `glpi_plugin_wilaya_reports` (
                  `id` int(4) NOT NULL AUTO_INCREMENT,
                  `name` varchar(100) NOT NULL,
                  `description` varchar(200) NOT NULL,
                  PRIMARY KEY (`id`,`name`,`description`),
                  UNIQUE KEY `name` (`name`),
                  KEY `name_2` (`name`)) 
                  ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";
        $DB->query($query) or die("Erreur lors de la création de la table glpi_plugin_wilaya_reports " . $DB->error());
        $DB->query("INSERT INTO glpi_plugin_wilaya_reports(name, description) VALUES('État des connexions réseaux', 'Afficher les ordinateurs connectés/non connectés aux réseaux')") or die("Erreur lors de l'insertion dans glpi_plugin_wilaya_config " . $DB->error());
    }  
    
   return true;
}

function plugin_wilaya_uninstall() {
   global $DB;

   $tables = array("glpi_plugin_wilaya_config", "glpi_plugin_wilaya_reports");

   foreach($tables as $table) {
        $DB->query("DROP TABLE IF EXISTS `$table`;");
   }
   return true;
}

//function plugin_change_profile_wilaya() {
//   $plugin = new Plugin();
//   if ($plugin->isActivated("wilaya")) {
//      if (/*Session::haveRight("logs", "r") || Session::haveRight("ocsng", "w")*/true) {
//         $PLUGIN_HOOKS['menu_entry']['wilaya'] = true;
//      } else {
//         $PLUGIN_HOOKS['menu_entry']['wilaya'] = false;
//      }
//   }
//}
//
//function plugin_wilaya_get_headings($type, $withtemplate) {
//    switch(get_Class($type))
//    {
//        case 'Computer' : return array( 1 => "Cl&eacute;s office"); break;
//    }
//    return false;
//}

//function plugin_wilaya_headings_actions($type) {
//    switch(get_Class($type))
//    {
//        case 'Computer' : return array( 1 => "plugin_getofficekeys_display"); break;
//    }
//    return false;
//}
//
//function plugin_wilaya_getDatabaseRelations() {
//   return array("glpi_plugin_wilaya_dropdowns" => array("glpi_wilaya_getofficekeys" => "plugin_wilaya_dropdowns_id"));
//}

?>