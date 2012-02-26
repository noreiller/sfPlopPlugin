<?php

// autoloading for plugin lib actions is broken as at symfony-1.0.2
require_once(sfConfig::get('sf_plugins_dir'). '/sfPlopPlugin/modules/sfPlopCMS/lib/BasesfPlopCMSActions.class.php');

/**
 * Actions
 *
 * @author Aurélien MANCA <aurelien.manca@gmail.com>
 */
class sfPlopCMSActions extends BasesfPlopCMSActions
{
}
?>
