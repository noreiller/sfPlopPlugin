<?php



/**
 * Skeleton subclass for performing query and update operations on the 'sf_plop_config' table.
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
class sfPlopConfigPeer extends BasesfPlopConfigPeer {

  /**
   * Retrieve all the config entries
   * @param BaseObject $con
   * @return Array BaseObject Collection
   */
  public static function retrieveAll($con = null)
  {
    return self::doSelect(new Criteria(), $con);
  }

  /**
   * Retrieve the config value by his name
   * @param String $name
   * @param PropelPDO $con
   * @return BaseObject
   */
  public static function retrieveByName($name, $con = null)
  {
    $c = new Criteria();
    $c->add(self::NAME, $name);

    return self::doSelectOne($c, $con);
  }

  /**
   * @see sfYamlInline::dump()
   * @param String $value
   * @return String Yaml value
   */
  public static function dump($value)
  {
    return sfYamlInline::dump($value);
  }

  /**
   * @see sfYamlInline::load()
   * @param String $value
   * @return Mixed PHP
   */
  public static function load($value)
  {
    return sfYamlInline::load($value);
  }

  /**
   * Check if two config values are identical
   * @param String $s1
   * @param String $s2
   * @return Boolean
   */
  public static function isIdentical($s1, $s2)
  {
    return (
        strlen(trim($s1)) != strlen(trim($s2))
        || strpos(trim($s1), trim($s2)) === false
      ) ? false : true;
  }

  /**
   * Check if the config needs to be updated given to his value.
   * @param String $name
   * @param String $value
   * @param PropelPDO $con
   */
  public static function addOrUpdate($name, $value, $con = null)
  {
    $config = sfPlopConfigPeer::retrieveByName($name, $con);
    $config_static = self::dump(sfPlop::get($name, true));
    $value_dump = self::dump($value);

    if ($config && !self::isIdentical($config->getValue(), $value_dump))
    {
      if ((is_string($value_dump) && trim($value_dump) == '') || self::isIdentical($value_dump, $config_static))
      {
        $config->delete();
      }
      elseif (!self::isIdentical($value_dump, $config_static))
      {
        $config->setValue($value_dump);
        $config->save();
      }
    }
    elseif (!$config && (trim($value_dump) != '') && !self::isIdentical($value_dump, $config_static))
    {
      $config = new sfPlopConfig();
      $config->setName($name);
      $config->setValue($value_dump);
      $config->save();
    }

    sfPlop::set($name, $value);
  }

} // sfPlopConfigPeer
