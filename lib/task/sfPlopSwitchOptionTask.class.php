<?php

class sfPlopSwitchOptionTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('app', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', null),
      new sfCommandOption('option', null, sfCommandOption::PARAMETER_REQUIRED, 'The name of the option to switch', null),
      new sfCommandOption('status', null, sfCommandOption::PARAMETER_REQUIRED, 'The status value : ON or OFF', null)
    ));

    $this->namespace        = 'plop';
    $this->name             = 'switch-option';
    $this->briefDescription = 'Switch the status of an option the application.';
    $this->detailedDescription = <<<EOF
The [switch-option|INFO] configure the application config file.
Call it with:

  [php symfony plop:switch-option --app=frontend --option=myoption --status=(ON|OFF) |INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $file = sfConfig::get('sf_apps_dir').'/'.$options['app'].'/config/app.yml';
    $config = file_exists($file) ? sfYaml::load($file) : array();

    $config = $this->switchConfig(
      $config, 
      strtolower($options['option']), 
      strtolower($options['status'])
    );

    file_put_contents($file, sfYaml::dump($config, 4));
  }

  protected function switchConfig($config, $name, $status)
  {
    $blocked_modules = $config['all']['sf_plop']['blocked_modules'];
    if (!is_array($blocked_modules))
      $blocked_modules = array();

    $blocked_slots = $config['all']['sf_plop']['blocked_slots'];
    if (!is_array($blocked_slots))
      $blocked_slots = array();

    $switch = $this->getConfigToSwitch($name);
    $modules = isset($switch['modules']) ? $switch['modules'] : array();
    $slots = isset($switch['slots']) ? $switch['slots'] : array();

    if (in_array($status, array('true', '1', 'on')))
    {
      foreach($modules as $module)
        $blocked_modules = $this->_removeValueFromArray($module, $blocked_modules);

      foreach($slots as $slot)
        $blocked_slots = $this->_removeValueFromArray($slot, $blocked_slots);
    }
    elseif (in_array($status, array('false', '0', 'off')))
    {
      foreach($modules as $module)
        $blocked_modules = $this->_pushValueIntoArray($module, $blocked_modules);

      foreach($slots as $slot)
        $blocked_slots = $this->_pushValueIntoArray($slot, $blocked_slots);
    }

    $config['all']['sf_plop']['blocked_modules'] = $blocked_modules;
    $config['all']['sf_plop']['blocked_slots'] = $blocked_slots;
    
    return $config;
  }
  
  protected function getConfigToSwitch($name)
  {
    $array = array(
      'medialibrary' => array(
        'modules' => array('sfAssetLibrary', 'sfAssetGallery'),
        'slots' => array('Asset', 'AssetGallery', 'AssetGalleryNavigation', 'CustomGalleryAsset')
      ),
      'multiuser' => array(
        'modules' => array('sfGuardUser', 'sfGuardGroup'),
        'slots' => array('LoginForm', 'RegisterForm')
      ),
      'googlemaps' => array(
        'slots' => array('GoogleMaps', 'GoogleMapsFilter', 'GoogleMapsPosition')
      )
    );

    return isset($array[$name]) ? $array[$name] : array();
  }

  protected function _pushValueIntoArray($v, $a)
  {
    $key = array_search($v, $a);
    if ($key === false)
      $a []= $v;
  
    return $a;
  }
  
  protected function _removeValueFromArray($v, $a)
  {
    $key = array_search($v, $a);
    if ($key !== false)
      unset($a[$key]);
  
    return $a;
  }
}

?>