<?php
/**
 * Description of sfPlopPluginConfiguration
 *
 * @author Aurélien MANCA <aurelien.manca@gmail.com>
 */
class sfPlopPluginConfiguration extends sfPluginConfiguration
{

  public function initialize()
  {
    // LISTENERS
    $this->dispatcher->connect('plop.messaging', array('sfPlopListeners', 'listenToMessagingEvent'));

    // CONFIG
    sfConfig::add(array(
      'sf_plop_version' => '2',
      'sf_plop_website_description' => 'Plop CMS - a fully customizable CMS',
      'sf_plop_website_keywords' => 'plop, cms',
      'sf_plop_use_custom_page_title' => true,
      'sf_plop_website_title' => 'Plop CMS',
      'sf_plop_website_title_position' => 'after',
      'sf_plop_website_title_prefix' => '-',
      'sf_plop_website_title_suffix' => '',
      'sf_plop_use_title_in_seo_description' => true,
      'sf_plop_default_culture' => 'fr',
      'sf_plop_cultures' => array('fr', 'en'),
      'sf_plop_use_country_flags' => true,
      'sf_plop_country_flags_path' => '/sfPlopPlugin/vendor/famfamfam/flags/',
      'sf_plop_loaded_admin_themes' => array(
        'admin' => array(
          'name' => 'admin',
          'description' => 'Default admin theme',
          'color' => '#444'
        ),
        'admin-theme-light' => array(
          'name' => 'admin-theme-light',
          'description' => 'Light version of the default admin theme',
          'color' => '#eee'
        )
      ),
      'sf_plop_admin_theme' => 'admin',
      'sf_plop_loaded_themes' => array(
        'none' => false,
        'victor' => array(
          'name' => 'victor',
          'description' => 'Victor grey',
          'location' => '/sfPlopPlugin/themes/victor/',
          'css' => array(
            '/sfPlopPlugin/themes/victor/css/theme.css',
            'http://fonts.googleapis.com/css?family=Kreon'
          ),
          'subthemes' => array(
            'victor victor-black' => 'Victor black',
//            'victor victor-burgundy' => 'Victor burgundy',
            'victor victor-blue' => 'Victor blue'
          )
        ),
        'dark' => array(
          'name' => 'dark',
          'description' => 'Dark',
          'location' => '/sfPlopPlugin/themes/dark/',
          'css' => '/sfPlopPlugin/themes/dark/css/theme.css'
        ),
        'florentine' => array(
          'name' => 'florentine',
          'description' => 'Florentine',
          'location' => '/sfPlopPlugin/themes/florentine/',
          'css' => '/sfPlopPlugin/themes/florentine/css/theme.css'
        ),
        'metal' => array(
          'name' => 'metal',
          'description' => 'Metal',
          'location' => '/sfPlopPlugin/themes/metal/',
          'css' => '/sfPlopPlugin/themes/metal/css/theme.css'
        ),
        'canvas' => array(
          'name' => 'mfgallery',
          'description' => 'Canvas',
          'location' => '/sfPlopPlugin/themes/canvas/',
          'css' => '/sfPlopPlugin/themes/canvas/css/theme.css'
        ),
        'kerbi' => array(
          'name' => 'kerbi',
          'description' => 'Kerbi',
          'location' => '/sfPlopPlugin/themes/kerbi/',
          'css' => array(
            '/sfPlopPlugin/themes/kerbi/css/theme.css',
            'http://fonts.googleapis.com/css?family=Kreon'
          )
        ),
        'architect' => array(
          'name' => 'architect',
          'description' => 'Architect',
          'location' => '/sfPlopPlugin/themes/architect/',
          'css' => array(
            '/sfPlopPlugin/themes/architect/css/theme.css',
            'http://fonts.googleapis.com/css?family=Terminal+Dosis'
          )
        )
      ),
      'sf_plop_theme' => 'victor',
      'sf_plop_custom_css' => null,
      'sf_plop_custom_js' => null,
      'sf_plop_custom_favicon' => '/sfPlopPlugin/images/favicon.ico',
      'sf_plop_custom_webapp_favicon' => '/sfPlopPlugin/images/apple-touch-icon.png',
      'sf_plop_use_html5' => true,
      'sf_plop_use_image_zoom' => false,
      'sf_plop_use_ajax' => false,
      'sf_plop_form_help_format' => '<div class="widget-form-help">%help%</div>',
      'sf_plop_use_statistics' => false,
      'sf_plop_statistics_code' => '',
      'sf_plop_slots_class_prefix' => 'sfPlopSlot',
      'sf_plop_allow_registration' => false,
      'sf_plop_private_access' => false,
      'sf_plop_menu_items' => array(
        'icon' => 'icon',
        'title' => 'title',
        'subtitle' => 'subtitle'
      ),
      'sf_plop_slot_layouts' => array(
        'l' => 'left',
        'c' => 'center',
        'r' => 'right',
        'lc' => 'left + center',
        'cr' => 'center + right',
        'ml' => 'midleft',
        'mr' => 'midright',
        'lcr' => 'left + center + right'
      ),
      'sf_plop_loaded_slots' => array(
        'RichText' => 'Rich text',
        'Text' => 'Simple text',
        'PageTitle' => 'Page title',
        'Area' => 'Blocks area',
        'PageHeader' => 'Page header',
        'PageFooter' => 'Page footer',
        'MainNavigation' => 'Main navigation',
        'SecondNavigation' => 'Second navigation',
        'LocaleNavigation' => 'Localization navigation',
        'LoginLinks' => 'Login links',
        'Breadcrumb' => 'Breadcrumb trail',
        'SiteMap' => 'Site map',
        'ContactForm' => 'Contact form',
        'LoginForm' => 'Login form',
        'RegisterForm' => 'Register form',
        'Code' => 'Code block',
//        'Redirection' => 'Redirection',
//        'XmlFeed' => 'Xml feed',
        'Date' => 'Date',
//        'Calendar' => 'Calendar',
//        'BlogArticle' => 'Blog article'
        'PoweredByPlopCMS' => 'Powered by Plop CMS',
        'ThemeSwitcher' => 'Theme switcher',
        'DistantGallery' => 'Distant gallery'
      ),
      'sf_plop_loaded_modules' => array(
        'sf_plop_cms' => array('name' => 'Contents', 'route' => '@sf_plop_homepage', 'culture' => 'default'),
        'sf_plop_dashboard' => array('name' => 'Dashboard', 'route' => '@sf_plop_dashboard')
      ),
      'sf_plop_loaded_links' => array(
        'sf_plop_homepage' => array('name' => 'Homepage', 'route' => '@sf_plop_homepage', 'culture' => 'default')
      ),
      'sf_plop_cache_lifetime' => 86400,
      'sf_plop_uncached_slots' => array(
        'Date',
        'ContactForm',
        'LoginForm',
        'RegisterForm'
      )
    ));

    // Enabled slots and modules for sfPlopCMS
    sfConfig::add(array(
      'sf_plop_enabled_slots' => array(
        'RichText',
        'Text',
        'PageTitle',
        'Area',
        'PageHeader',
        'PageFooter',
        'MainNavigation',
        'SecondNavigation',
        'LocaleNavigation',
        'LoginLinks',
        'Breadcrumb',
        'SiteMap',
        'ContactForm',
        'LoginForm',
        'RegisterForm',
        'Code',
        'Date',
        'ThemeSwitcher',
        'PoweredByPlopCMS',
        'DistantGallery',
        // sfAssetsGalleryPlugin
        'Asset',
        'AssetGallery',
        'CustomGalleryAsset',
        'AssetGalleryNavigation',
        // sfGoogleMapsPlugin
        'GoogleMaps',
        'GoogleMapsFilter',
        'GoogleMapsPosition'
      ),
      'sf_plop_enabled_modules' => array(
        'sf_plop_cms',
        'sf_plop_dashboard',
        // sfAssetsGalleryPlugin
        'sfAssetLibrary',
        'sfAssetGallery'
      ),
      'sf_plop_enabled_links' => array(
        'sf_plop_homepage'
      )
    ));

    // Configuration for sfPlopDashoard
    sfConfig::add(array(
      'sf_plop_dashboard_show_welcome_message' => true,
      'sf_plop_dashboard_show_browser_recommandations' => true,
      'sf_plop_dashboard_show_news' => false, //true,
      'sf_plop_dashboard_show_stats' => false, //true,
    ));
    // Configuration for sfPlopDashoard / non-overridable settings
    sfConfig::add(array(
      'sf_plop_dashboard_settings_tabs' => array(
        'seo' => 'SEO',
        'pluginModules' => 'Modules manager',
        'pluginSlots' => 'Content blocks manager',
        'statistics' => 'Statistics',
        'culture' => 'Content languages',
        'messaging' => 'Messaging',
        // 'theme' => 'Theme',
        'appearance' => 'Appearance',
        'access' => 'Public access',
//        'dashboard' => 'Dashboard homepage',
//        'feed' => 'Feed',
      )
    ));

    // Configuration for sfPlopMessaging
    sfConfig::add(array(
      'sf_plop_messaging_from_email' => 'anon@ymous.com',
      'sf_plop_messaging_from_name' => 'Anon YMOUS',
      'sf_plop_messaging_to_email' => 'anon@ymous.com',
      'sf_plop_messaging_to_name' => 'Anon YMOUS',
      'sf_plop_messaging_subject' => 'Your plop website has a message for you',
      'sf_plop_messaging_message' => 'Message from your plop website',
    ));

    // Load dynamic config
    sfPlop::check();
    sfPlop::loadPlugin(array(
      'slots' => sfPlop::get('sf_plop_loaded_slots', true),
      'modules' => sfPlop::get('sf_plop_loaded_modules', true),
      'themes' => sfPlop::get('sf_plop_loaded_themes', true),
      'links' => sfPlop::get('sf_plop_loaded_links', true)
    ));

    // CSS values
    sfConfig::add(array(
      'sf_plop_css_background-repeat' => array(
        'no-repeat' => 'No repetition',
        'repeat' => 'Repetition',
        'repeat-x' => 'Horizontal repetition',
        'repeat-y' => 'Vertical repetition'
      ),
      'sf_plop_css_background-position-x' => array(
        'left' => 'Left',
        'center' => 'Center',
        'right' => 'Right'
      ),
      'sf_plop_css_background-position-y' => array(
        'top' => 'Top',
        'center' => 'Middle',
        'bottom' => 'Bottom'
      ),
      'sf_plop_css_border-style' => array(
        'solid' => 'Solid',
        'dashed' => 'Dashed',
        'dotted' => 'Dotted'
      ),
      'sf_plop_css_font-family' => array(
        'Arial, Helvetica, sans-serif' => 'Arial, Helvetica, sans-serif',
        'Verdana, Arial, Helvetica, sans-serif' => 'Verdana, Arial, Helvetica, sans-serif',
        'Georgia, "Times New Roman", Times, serif' => 'Georgia, "Times New Roman", Times, serif',
        '"Times New Roman", Times, serif' => '"Times New Roman", Times, serif',
        '"Courier New", Courier, mono' => '"Courier New", Courier, mono'
      ),
      'sf_plop_css_font-size' => array(
        '.75em' => '1 (75%)',
        '1em' => '2 (100%)',
        '1.2em' => '3 (120%)',
        '1.4em' => '4 (140%)',
        '1.6em' => '5 (160%)'
      ),
      'sf_plop_css_border-width' => array(
        '0px' => '0px',
        '1px' => '1px',
        '2px' => '2px',
        '3px' => '3px',
        '4px' => '4px',
        '5px' => '5px',
        '6px' => '6px',
        '7px' => '7px',
        '8px' => '8px',
        '9px' => '9px',
        '10px' => '10px'
      ),
      'sf_plop_css_website-width' => array(
        '100%' => 'Fluid layout (100%)',
        '1000px' => 'Screen resolution of 1280x1024 (1000px)',
        '960px' => 'Screen resolution of 1024x768 (960px)',
        '750px' => 'Screen resolution of 800x600 (750px)',
        '320px' => 'Mobile (320px)',
      ),
    ));
  }
}
?>
