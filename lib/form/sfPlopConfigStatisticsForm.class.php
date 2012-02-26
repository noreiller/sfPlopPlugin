<?php

/**
 * sfPlopConfig form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopConfigStatisticsForm extends sfPlopConfigForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'sf_plop_use_statistics' => new sfWidgetFormInputCheckbox(array(
        'label' => 'Use statistics ?'
      ), array(
        'checked' => ((sfPlop::get('sf_plop_use_statistics') == true) ? 'checked' : null),
      )),
      'sf_plop_statistics_code' => new sfWidgetFormTextarea(array(
        'default' => sfPlop::get('sf_plop_statistics_code'),
        'label' => 'Statistics code'
      )),
      'sf_plop_statistics_reports_url' => new sfWidgetFormInputText(array(
        'label' => 'Statistics reports url'
      ), array(
        'value' => sfPlop::get('sf_plop_statistics_reports_url')
      ))
    ));

    $this->setDefault('sf_plop_statistics_code', sfPlop::get('sf_plop_statistics_code'));

    $this->setValidators(array(
      'sf_plop_use_statistics' => new sfValidatorBoolean(),
      'sf_plop_statistics_code' => new sfValidatorString(array('required' => false)),
      'sf_plop_statistics_reports_url' => new sfValidatorUrl(array('required' => false)),
    ));

    parent::configure();
  }
}
