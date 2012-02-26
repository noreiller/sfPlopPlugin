<?php
/**
 * Util class.
 *
 * @author Aurélien MANCA <aurelien.manca@gmail.com>
 */
class sfPlop {

  /**
   * Returns the prefix of the custom config.
   * @param String $link
   * @return String
   */
  protected static function prefix($link = '.')
  {
    return 'plop' . $link;
  }

  /**
   * Check if custom config is set.
   * @return Boolean
   */
  public static function is_set()
  {
    return sfConfig::get(self::prefix('')) == true ? true : false;
  }

  /**
   * Check if custom config is ready to use.
   * @return Boolean
   */
  public static function is_ready()
  {
    try {
      return
        class_exists('BasesfPlopConfigPeer')
      ;
    } catch (Exception $e) {
      return false;
    }
  }

  /**
   * Clear conf for a given key
   * @param String $key
   */
  public static function clear($key)
  {
    if (sfConfig::has($key))
      sfConfig::set($key, null);
  }

  /**
   * Clear conf for a given key
   */
  public static function clearAll()
  {
    foreach (sfConfig::getAll() as $key => $value)
    {
      if (preg_match('/^' . self::prefix() . '/', $key, $matches))
        if (isset($matches[0][1]))
          self::clear($matches[0][1]);
    }
  }

  /**
   * Retrieve, for a given key, the config in the app, otherwise in plugin conf.
   *
   * @param String $key
   * @param Boolean $force_static
   * @param PropelPDO $con
   *
   * @return String
   */
  public static function get($key = null, $force_static = false, PropelPDO $con = null)
  {
    if (!$key)
      return;
    else
      $value = null;

    if (!$force_static)
    {
      self::check($con);
      $value = sfConfig::get(self::prefix() . $key, null);
    }

    if ($force_static || (!is_bool($value) && !$value))
      $value = sfConfig::get('app_' . $key, sfConfig::get($key));

    return $value;
  }

  /**
   * Set the custom config for a given key
   * @param String $key
   * @param String $value
   * @param PropelPDO $con
   */
  public static function set($key = null, $value = null, PropelPDO $con = null)
  {
    if (!$key)
      return;

    self::check($con);

    sfConfig::set(self::prefix() . $key, $value);
  }

  /**
   * Check if custom sfConfig params are loaded. If not, load them.
   * @param PropelPDO $con
   */
  public static function check($con = null)
  {
    if (!self::is_set() && self::is_ready())
    {
      if (!$con && !sfProjectConfiguration::hasActive())
      {
        throw new Exception('The Project configuration is not loaded.');
      }
      elseif (!$con)
      {
        $configuration = sfProjectConfiguration::getActive();
        $databaseManager = new sfDatabaseManager($configuration);
        $con = $databaseManager->getDatabase(sfConfig::get('sf_orm'))->getConnection();
        $init = $databaseManager->initialize($configuration);
      }

      try {
        $config = sfPlopConfigPeer::retrieveAll($con);
        foreach($config as $obj)
          sfConfig::set(
            self::prefix() . $obj->getName(),
            sfPlopConfigPeer::load($obj->getValue())
          );

        sfConfig::set(self::prefix(''), true);
      } catch (Exception $e) {
        return false;
      }
    }
  }

  /**
   * Load a Plop plugin which can contain modules and slots
   * @param Array $options
   */
  public static function loadPlugin($options)
  {
    if (self::is_set() && self::is_ready())
    {
      $plugin_options = array(
        'modules' => 'sf_plop_loaded_modules',
        'slots' => 'sf_plop_loaded_slots',
        'themes' => 'sf_plop_loaded_themes',
        'links' => 'sf_plop_loaded_links'
      );

      foreach ($plugin_options as $plugin_option => $plugin_config)
      {
        if (isset($options[$plugin_option]))
        {
          $loaded_options = sfPlop::get($plugin_config);
          foreach($options[$plugin_option] as $name => $desc)
            $loaded_options[$name] = $desc;
          sfPlopConfigPeer::addOrUpdate($plugin_config, $loaded_options);
        }
      }
    }
  }

  /**
   * Get the safe list of the plugin slots
   * @return Array $return
   */
  public static function getSafePluginSlots($include_disabled = false)
  {
    $exceptions = array();
    $slots = self::get('sf_plop_loaded_slots');
    $enabled = self::get('sf_plop_enabled_slots');
    $blocked = sfConfig::get('app_sf_plop_blocked_slots', array());

    if (!is_array($blocked))
      $blocked = array($blocked);

    foreach ($slots as $slot => $infos)
      if ((
        (!in_array($slot, $enabled) && !$include_disabled)
          && !in_array($slot, $exceptions)
        ) || in_array($slot, $blocked))
        unset($slots[$slot]);

    if (!$include_disabled 
      && sfPlop::get('sf_plop_allow_registration') !== true 
      && isset($slots['RegisterForm'])
    )
      unset($slots['RegisterForm']);

    return $slots;
  }

  /**
   * Get the safe list of the plugin modules
   * @return Array $return
   */
  public static function getSafePluginModules($include_disabled = false)
  {
    $exceptions = array('sf_plop_cms', 'sf_plop_dashboard');
    $modules = self::get('sf_plop_loaded_modules');
    $enabled = self::get('sf_plop_enabled_modules');
    $blocked = sfConfig::get('app_sf_plop_blocked_modules', array());

    if (!is_array($blocked))
      $blocked = array($blocked);

    foreach ($modules as $module => $infos)
      if ((
        (!in_array($module, $enabled) && !$include_disabled)
          && !in_array($module, $exceptions)
        ) || in_array($module, $blocked))
        unset($modules[$module]);

    return $modules;
  }

  /**
   * Get the safe list of the plugin links
   * @return Array $return
   */
  public static function getSafePluginLinks($include_disabled = false)
  {
    $exceptions = array();
    $modules = self::get('sf_plop_loaded_links');
    $enabled = self::get('sf_plop_enabled_links');
    $blocked = sfConfig::get('app_sf_plop_blocked_links', array());

    if (!is_array($blocked))
      $blocked = array($blocked);

    foreach ($modules as $module => $infos)
      if ((
				(!in_array($module, $enabled) && !$include_disabled)
					&& !in_array($module, $exceptions)
				) || in_array($module, $blocked))
        unset($modules[$module]);

    return $modules;
  }

  /**
   * Get the full list of themes including the subthemes
   * @return Array $return
   */
  public static function getAllThemes()
  {
    $themes = self::get('sf_plop_loaded_themes');
    $all_themes = array();

    foreach ($themes as $theme => $theme_infos)
    {
      $name = isset($theme_infos['name'])
        ? $theme_infos['name']
        : $theme;
      $label = isset($theme_infos['description'])
        ? $theme_infos['description']
        : $name;
      if (!isset($theme_infos['subthemes']))
        $all_themes [$theme] = $label;
      else
        $all_themes [$theme] = array($theme => $label) + $theme_infos['subthemes'];
    }

    return $all_themes;
  }

  /**
   * Get the admin theme
   * @param Boolean $is_logged
   * @param Boolean $is_private
   * @return String
   */
  public static function getAdminTheme($theme = null, $is_logged = false, $is_private = false)
  {
    $return = '';

    if ($is_logged)
      $return .= ' admin';

    if ($is_logged && $is_private)
      $return .= ' admin-bo';

    if (!$theme)
      $theme = sfPlop::get('sf_plop_admin_theme');
    if ($theme && $theme != sfPlop::get('sf_plop_admin_theme', true))
      $return .= ' ' . $theme;

    return $return;
  }

  /**
   * Render a title within options such as prefix, suffix and the website one.
   * @param String $title
   * @return String
   */
  public static function setMetaTitle($title = '')
  {
    if (sfPlop::get('sf_plop_use_custom_page_title') == true)
    {
      $page_title_pos = sfPlop::get('sf_plop_website_title_position');
      $website_title = sfPlop::get('sf_plop_website_title');
      $website_title_p = sfPlop::get('sf_plop_website_title_prefix');
      $website_title_s = sfPlop::get('sf_plop_website_title_suffix');
      $page_title = $website_title_p . ' ' . $website_title . ' ' . $website_title_s;

      $title = ($page_title_pos == 'after') ? $title . ' ' . $page_title : $page_title . ' ' . $title;
    }

    return $title;
  }

  /**
   * Clear the cache
   */
  public static function emptyCache()
  {
    try {
      $cache = new sfFileCache(array(
        'cache_dir' => sfConfig::get('sf_cache_dir')
      ));
      $cache->clean();
      self::clearAll();
    } catch (Exception $e) {
    }
  }

  /**
   * Get Plop CMS version
   */
  public static function getVersion()
  {
    $version = sfPlop::get('sf_plop_version');
    $file = sfConfig::get('sf_plugins_dir') . '/sfPlopPlugin/VERSION';
    if ($handle = fopen($file, 'r'))
    {
      $content = fread($handle, filesize($file));
      if (preg_match('/([._-a-z0-9]+)/', $content, $matches))
      {
        $version = $matches[1];
      }
      fclose($handle);
    }

    return $version;
  }

}
?>
