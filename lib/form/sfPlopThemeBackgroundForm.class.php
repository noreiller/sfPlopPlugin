<?php

/**
 * sfPlopThemeCommon form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopThemeBackgroundForm extends sfPlopConfigForm
{
  public function getFormLabel() {
    return 'Website background';
  }

  public function configure()
  {
    parent::configure();

    $selector = 'body';

    $this->setWidgets(array(
      'sf_plop_background_image' => new sfWidgetFormAssetExtraChoice(
        array(
          'label' => 'Background image'
        ),
        array(
          'value' => sfPlop::get('sf_plop_background_image'),
          'data-property' => 'background-image',
          'data-target' => $selector
        )),
      'sf_plop_background_color' => new sfWidgetFormInputColor(
        array(
          'label' => 'Background color'
        ),
        array(
          'value' => sfPlop::get('sf_plop_background_color'),
          'data-property' => 'background-color',
          'data-target' => $selector
        )),
      'sf_plop_background_repeat' => new sfWidgetFormCssProperty(
        array(
          'property' => 'background-repeat',
          'label' => 'Background repetition'
        ),
        array(
          'value' => sfPlop::get('sf_plop_background_repeat'),
          'data-property' => 'background-repeat',
          'data-target' => $selector
        )),
      'sf_plop_background_position_horizontal' => new sfWidgetFormCssProperty(
        array(
          'property' => 'background-position-x',
          'label' => 'Horizontal background position'
        ),
        array(
          'value' => sfPlop::get('sf_plop_background_position_horizontal'),
          'data-property' => 'background-position-x',
          'data-target' => $selector
        )),
      'sf_plop_background_position_vertical' => new sfWidgetFormCssProperty(
        array(
          'property' => 'background-position-y',
          'label' => 'Vertical background position'
        ),
        array(
          'value' => sfPlop::get('sf_plop_background_position_vertical'),
          'data-property' => 'background-position-y',
          'data-target' => $selector
        )),
    ));

    $this->setValidators(array(
      'sf_plop_background_image' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_background_color' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_background_repeat' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_background_position_horizontal' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_background_position_vertical' => new sfValidatorString(array(
        'required' => false
      )),
    ));

    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('plopAdmin');
  }
}
