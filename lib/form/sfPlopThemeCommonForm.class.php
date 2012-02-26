<?php

/**
 * sfPlopThemeCommon form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopThemeCommonForm extends sfPlopConfigForm
{
  public function getFormLabel() {
    return 'Common';
  }

  public function configure()
  {
    parent::configure();

    $selector = '#container';

    $this->setWidgets(array(
      'sf_plop_icon' => new sfWidgetFormAssetExtraChoice(
        array(
          'label' => 'Favicon'
        ),
        array('value' => sfPlop::get('sf_plop_icon'))),
      'sf_plop_css' => new sfWidgetFormAssetExtraChoice(
        array(
          'label' => 'Stylesheet'
        ),
        array('value' => sfPlop::get('sf_plop_css'))),
      'sf_plop_js' => new sfWidgetFormAssetExtraChoice(
        array(
          'label' => 'Javascript'
        ),
        array('value' => sfPlop::get('sf_plop_js'))),
      'sf_plop_website_width' => new sfWidgetFormCssProperty(
        array(
          'property' => 'website-width',
          'label' => 'Website width'
        ),
        array(
          'value' => sfPlop::get('sf_plop_website_width'),
          'data-property' => 'width',
          'data-target' => $selector
        )),
    ));

    $this->setValidators(array(
      'sf_plop_icon' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_css' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_js' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_website_width' => new sfValidatorString(array(
        'required' => false
      )),
    ));

    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('plopAdmin');
  }
}
