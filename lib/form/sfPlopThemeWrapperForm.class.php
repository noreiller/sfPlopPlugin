<?php

/**
 * sfPlopThemeCommon form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopThemeWrapperForm extends sfPlopConfigForm
{
  public function getFormLabel() {
    return 'Wrapper style';
  }

  public function configure()
  {
    parent::configure();

    $selector = '#container';

    $this->setWidgets(array(
      'sf_plop_wrapper_background_image' => new sfWidgetFormAssetExtraChoice(
        array(
          'label' => 'Background image'
        ),
        array(
          'value' => sfPlop::get('sf_plop_wrapper_background_image'),
          'data-property' => 'background-image',
          'data-target' => $selector
        )),
      'sf_plop_wrapper_background_color' => new sfWidgetFormInputColor(
        array(
          'label' => 'Background color'
        ),
        array(
          'value' => sfPlop::get('sf_plop_wrapper_background_color'),
          'data-property' => 'background-color',
          'data-target' => $selector
        )),
      'sf_plop_wrapper_background_repeat' => new sfWidgetFormCssProperty(
        array(
          'property' => 'background-repeat',
          'label' => 'Background repetition'
        ),
        array(
          'value' => sfPlop::get('sf_plop_wrapper_background_repeat'),
          'data-property' => 'background-repeat',
          'data-target' => $selector
        )),
      'sf_plop_wrapper_background_position_horizontal' => new sfWidgetFormCssProperty(
        array(
          'property' => 'background-position-x',
          'label' => 'Horizontal background position'
        ),
        array(
          'value' => sfPlop::get('sf_plop_wrapper_background_position_horizontal'),
          'data-property' => 'background-position-x',
          'data-target' => $selector
        )),
      'sf_plop_wrapper_background_position_vertical' => new sfWidgetFormCssProperty(
        array(
          'property' => 'background-position-y',
          'label' => 'Vertical background position'
        ),
        array(
          'value' => sfPlop::get('sf_plop_wrapper_background_position_vertical'),
          'data-property' => 'background-position-y',
          'data-target' => $selector
        )),
      'sf_plop_wrapper_border_color' => new sfWidgetFormInputColor(
        array(
          'label' => 'Border color'
        ),
        array(
          'value' => sfPlop::get('sf_plop_wrapper_border_color'),
          'data-property' => 'border-color',
          'data-target' => $selector
        )),
      'sf_plop_wrapper_border_size' => new sfWidgetFormCssProperty(
        array(
          'property' => 'border-width',
          'label' => 'Border size'
        ),
        array(
          'value' => sfPlop::get('sf_plop_wrapper_border_size'),
          'data-property' => 'border-width',
          'data-target' => $selector
        )),
      'sf_plop_wrapper_border_style' => new sfWidgetFormCssProperty(
        array(
          'property' => 'border-style',
          'label' => 'Border style'
        ),
        array(
          'value' => sfPlop::get('sf_plop_wrapper_border_style'),
          'data-property' => 'border-style',
          'data-target' => $selector
        )),
    ));

    $this->setValidators(array(
      'sf_plop_wrapper_background_image' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_wrapper_background_color' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_wrapper_background_repeat' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_wrapper_background_position_horizontal' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_wrapper_background_position_vertical' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_wrapper_border_color' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_wrapper_border_size' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_wrapper_border_style' => new sfValidatorString(array(
        'required' => false
      )),
    ));

    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('plopAdmin');
  }
}
