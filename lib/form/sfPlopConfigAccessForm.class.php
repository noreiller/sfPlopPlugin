<?php

/**
 * sfPlopConfigAccess form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopConfigAccessForm extends sfPlopConfigForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'sf_plop_allow_registration' => new sfWidgetFormInputCheckbox(array(
        'label' => 'Allow registration ?'
      ), array(
        'checked' => ((sfPlop::get('sf_plop_allow_registration') == true) ? 'checked' : null),
      )),
      'sf_plop_private_access' => new sfWidgetFormInputCheckbox(array(
        'label' => 'Restrict public access ?'
      ), array(
        'checked' => ((sfPlop::get('sf_plop_private_access') == true) ? 'checked' : null),
      )),
      'sf_plop_cache_lifetime' => new sfWidgetFormChoice(array(
        'label' => 'Cache lifetime',
        'choices' => $this->getCacheLifetimeValues()
      ))
    ));

    $this->setValidators(array(
      'sf_plop_allow_registration' => new sfValidatorBoolean(),
      'sf_plop_private_access' => new sfValidatorBoolean(),
      'sf_plop_cache_lifetime' => new sfValidatorChoice(array(
        'choices' => array_keys($this->getCacheLifetimeValues())
      ))
    ));

    $this->setDefault('sf_plop_cache_lifetime', sfPlop::get('sf_plop_cache_lifetime'));

    parent::configure();
  }

  protected function getCacheLifetimeValues()
  {
    return array(
      '604800' => '1 week',
      '86400' => '1 day',
      '33200' => '12 hours',
      '3600' => '1 hour'
    );
  }
}
