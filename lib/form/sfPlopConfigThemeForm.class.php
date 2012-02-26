<?php

/**
 * sfPlopConfig form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopConfigThemeForm extends sfPlopConfigForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'sf_plop_theme' => new sfWidgetFormPlopChoiceTheme(array(
        'label' => 'Theme',
        'add_empty' => true
      )),
      'sf_plop_theme_preview' => new sfWidgetFormInputCheckbox(array(
        'label' => 'Preview',
      ))
    ));

    $this->setValidators(array(
      'sf_plop_theme' => new sfValidatorPlopChoiceTheme(array(
        'required' => false
      )),
      'sf_plop_theme_preview' => new sfValidatorBoolean()
    ));

    $this->widgetSchema->setHelps(array(
      'sf_plop_theme_preview' => 'Check this option if you only want to preview the selected theme.'
    ));

    parent::configure();
  }

  public function save()
  {
    unset($this->values['sf_plop_theme_preview']);
    return parent::save();
  }
}
