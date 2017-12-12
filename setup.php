<?php
/* Nom du fichier: setup.php
   Auteur: Soufiane
*/

class PluginWilayaConfig extends CommonDBTM {

   static protected $notable = true;
   
   /**
    * @see CommonGLPI::getMenuName()
   **/
   static function getMenuName() {
      return __('Wilaya');
   }
   
   /**
    *  @see CommonGLPI::getMenuContent()
    *
    *  @since version 0.5.6
   **/
   static function getMenuContent() {
   	global $CFG_GLPI;
   
   	$menu = array();

      $menu['title']   = __('Wilaya','wilaya');
      $menu['page']    = '/plugins/wilaya/front/index.php';
   	return $menu;
   }
}

function plugin_init_wilaya() {
   global $PLUGIN_HOOKS, $LANG;

   $PLUGIN_HOOKS['add_css']['wilaya'][] = 'css/w_grid.css';
   $PLUGIN_HOOKS['add_css']['wilaya'][] = 'css/font-awesome.min.css';
   $PLUGIN_HOOKS['add_css']['wilaya'][] = 'css/wilaya.css';
   $PLUGIN_HOOKS['csrf_compliant']['wilaya'] = true;
   $PLUGIN_HOOKS["menu_toadd"]['wilaya'] = array('plugins'  => 'PluginWilayaConfig');
   $PLUGIN_HOOKS['config_page']['wilaya'] = 'front/index.php';

//   Plugin::registerClass('PluginWilayaRapport');
//   Plugin::registerClass('PluginWilayaProfile', array('addtabon' => array('Profile')));
//   Plugin::registerClass('PluginWilayaConfig');
    
   $plugin = new Plugin();
   if($plugin->isActivated("wilaya")) {
       
   }
}

function plugin_version_wilaya() 
{
    return array('name'           => "Wilaya",
                 'version'        => '0.1.0',
                 'author'         => '<a href="mailto:elghrabisoufiane@gmail.com"> Soufiane ELGHRABI </b> </a>',
                 'license'        => 'GPLv2+',
                 'homepage'       => 'https://forge.glpi-project.org/projects/wilaya',
                 'minGlpiVersion' => '0.85'
                );
}

function plugin_wilaya_check_prerequisites() 
{
  if (GLPI_VERSION >= 0.85)
   return true;
  echo "A besoin de la version 0.85 au minimum";
  return false; 
}

function plugin_wilaya_check_config($verbose=false) 
{
    if (true) {
        return true;
    }
    if ($verbose) {
        echo 'Installé / non configuré';
    }
    return false;
}

?>