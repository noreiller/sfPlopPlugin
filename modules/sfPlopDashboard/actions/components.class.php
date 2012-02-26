<?php

// autoloading for plugin lib actions is broken as at symfony-1.0.2
require_once(sfConfig::get('sf_plugins_dir'). '/sfPlopPlugin/modules/sfPlopDashboard/lib/BasesfPlopDashboardComponents.class.php');

class sfPlopDashboardComponents extends BasesfPlopDashboardComponents
{
}
