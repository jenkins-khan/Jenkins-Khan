<?php

require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
require_once dirname(__FILE__).'/../lib/vendor/jenkins-php-api/Autoload.php';
sfCoreAutoload::register();

Jenkins_Autoloader::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins('sfPropelORMPlugin');
    $this->enablePlugins('sfGuardPlugin');
    $this->enablePlugins('sfLESSPlugin');
    
    sfWidgetFormSchema::setDefaultFormFormatterName('div');

  }
  
  
}
