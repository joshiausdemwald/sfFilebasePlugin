<?php

require_once dirname(__FILE__).'/../lib/vendor/symfony/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    // for compatibility / remove and enable only the plugins you want
    $this->enableAllPluginsExcept(array('sfPropelPlugin', 'sfCompat10Plugin'));
    sfConfig::set('sfDoctrinePlugin_doctrine_lib_path', sfConfig::get('sf_lib_dir') . '/vendor/doctrine/Doctrine.php');
  }
}
