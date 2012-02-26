<?php

/**
 * sfPlopThemeCommon form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopThemeTextForm extends sfPlopConfigForm
{
  public function getFormLabel() {
    return 'Text';
  }

  public function configure()
  {
    parent::configure();

    $selector = '#container .w-block';

    $this->setWidgets(array(
      'sf_plop_block_text_font' => new sfWidgetFormCssProperty(
        array(
          'property' => 'font-family',
          'label' => 'Font family'
        ),
        array(
          'value' => sfPlop::get('sf_plop_block_text_font'),
          'title' => 'font-family',
          'data-target' => $selector
        )),
      'sf_plop_block_text_size' => new sfWidgetFormCssProperty(
        array(
          'property' => 'font-size',
          'label' => 'Font size'
        ),
        array(
          'value' => sfPlop::get('sf_plop_block_text_size'),
          'title' => 'font-size',
          'data-target' => $selector
        )),
      'sf_plop_block_text_color' => new sfWidgetFormInputColor(
        array(
          'label' => 'Text color'
        ),
        array(
          'value' => sfPlop::get('sf_plop_block_text_color'),
          'title' => 'color',
          'data-target' => $selector
        )),
      'sf_plop_block_link_color' => new sfWidgetFormInputColor(
        array(
          'label' => 'Link color'
        ),
        array(
          'value' => sfPlop::get('sf_plop_block_link_color'),
          'title' => 'color',
          'data-target' => $selector . ' a'
        )),
      'sf_plop_block_bold_color' => new sfWidgetFormInputColor(
        array(
          'label' => 'Bold text color'
        ),
        array(
          'value' => sfPlop::get('sf_plop_block_bold_color'),
          'title' => 'color',
          'data-target' => $selector . ' b,' . $selector . ' strong'
        )),
      'sf_plop_block_italic_color' => new sfWidgetFormInputColor(
        array(
          'label' => 'Italic text color'
        ),
        array(
          'value' => sfPlop::get('sf_plop_block_italic_color'),
          'title' => 'color',
          'data-target' => $selector . ' i,' . $selector . ' em'
        )),
    ));

    $this->setValidators(array(
      'sf_plop_block_text_font' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_block_text_size' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_block_text_color' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_block_link_color' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_block_bold_color' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_block_italic_color' => new sfValidatorString(array(
        'required' => false
      )),
    ));

    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('plopAdmin');
  }
}
