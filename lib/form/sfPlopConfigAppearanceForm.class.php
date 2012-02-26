<?php

/**
 * sfPlopConfig form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopConfigAppearanceForm extends sfPlopConfigForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'sf_plop_custom_favicon' => new sfWidgetFormAssetExtraChoice(array(
        'label' => 'Favicon',
      ), array(
        'value' => sfPlop::get('sf_plop_custom_favicon')
      )),
      'sf_plop_custom_webapp_favicon' => new sfWidgetFormAssetExtraChoice(array(
        'label' => 'Webapp icon',
      ), array(
        'value' => sfPlop::get('sf_plop_custom_webapp_favicon')
      )),
      'sf_plop_use_image_zoom' => new sfWidgetFormInputCheckbox(array(
        'label' => 'Use image zoom ?',
      ), array(
        'checked' => ((sfPlop::get('sf_plop_use_image_zoom') === true) ? 'checked' : null),
      )),
      'sf_plop_use_html5' => new sfWidgetFormInputCheckbox(array(
        'label' => 'Use HTML5 ?',
      ), array(
        'checked' => ((sfPlop::get('sf_plop_use_html5') === true) ? 'checked' : null),
      )),
      'sf_plop_use_ajax' => new sfWidgetFormInputCheckbox(array(
        'label' => 'Use Ajax in navigation menus ?'
      ), array(
        'checked' => ((sfPlop::get('sf_plop_use_ajax') === true) ? 'checked' : null)
      )),
      'sf_plop_css' => new sfWidgetFormAssetExtraChoice(array(
        'label' => 'Stylesheet' 
      ), array(
        'value' => sfPlop::get('sf_plop_css')
      )),
      'sf_plop_js' => new sfWidgetFormAssetExtraChoice( array(
        'label' => 'Javascript'
      ), array(
        'value' => sfPlop::get('sf_plop_js')
      )),
      'sf_plop_admin_theme' => new sfWidgetFormPlopChoiceAdminTheme(array(
        'label' => 'Admin theme',
        'add_empty' => true
      ))
    ));

    $this->setDefault('sf_plop_admin_theme', sfPlop::get('sf_plop_admin_theme'));

    $this->setValidators(array(
      'sf_plop_custom_favicon' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_custom_webapp_favicon' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_use_image_zoom' => new sfValidatorBoolean(),
      'sf_plop_use_html5' => new sfValidatorBoolean(),
      'sf_plop_use_ajax' => new sfValidatorBoolean(),
      'sf_plop_admin_theme' => new sfValidatorPlopChoiceAdminTheme(array(
        'required' => false
      )),
      'sf_plop_css' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_js' => new sfValidatorString(array(
        'required' => false
      ))
    ));

    $this->widgetSchema->setHelps(array(
      'sf_plop_custom_webapp_favicon' => 'This is used on smartphones when you create a shorcut on the desktop.'
    ));

    parent::configure();
  }
}
