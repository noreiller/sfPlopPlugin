<?php



/**
 * Skeleton subclass for representing a row from the 'sf_plop_config' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.6.0-dev on:
 *
 * Mon Mar 28 09:18:51 2011
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.plugins.sfPlopPlugin.lib.model
 */
class sfPlopConfig extends BasesfPlopConfig {

  /**
   * Return a PHP value of the config value which is formatted to Yaml
   * @return Mixed
   */
  public function getPhpValue()
  {
    return sfPlopConfigPeer::load($this->getValue());
  }

} // sfPlopConfig
