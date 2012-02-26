<?php

/**
 * sfPlopConfig form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopConfigCultureForm extends sfPlopConfigForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'sf_plop_default_culture' => new sfWidgetFormI18nChoiceLanguage(array(
        'culture' => sfPlop::get('sf_plop_default_culture'),
        'languages' => sfPlop::get('sf_plop_cultures')
      )),
      'sf_plop_cultures' => new sfWidgetFormPlopI18nChoiceLanguage(array(
        'expanded' => true,
        'culture' => sfPlop::get('sf_plop_default_culture'),
        'multiple' => true
      ))
    ));

    $this->setDefault('sf_plop_default_culture', sfPlop::get('sf_plop_default_culture'));
    $this->setDefault('sf_plop_cultures', sfPlop::get('sf_plop_cultures'));

    $this->widgetSchema->setLabels(array(
      'sf_plop_default_culture' => 'Default language',
      'sf_plop_cultures' => 'Languages',
    ));

    $this->setValidators(array(
      'sf_plop_default_culture' => new sfValidatorI18nChoiceLanguage(array(
        'languages' => sfPlop::get('sf_plop_cultures')
      )),
      'sf_plop_cultures' => new sfValidatorI18nChoiceLanguage(array(
        'multiple' => true
      ))
    ));

    parent::configure();
  }
}
