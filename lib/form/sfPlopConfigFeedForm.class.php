<?php

/**
 * sfPlopConfigAccess form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopConfigFeedForm extends sfPlopConfigForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'sfPlopCMS_use_feed' => new sfWidgetFormInputCheckbox(array(
        'label' => 'Use feed ?'
      ), array(
        'checked' => ((sfPlop::get('sfPlopCMS_use_feed') == true) ? 'checked' : null),
      ))

    ));

    $this->setValidators(array(
      'sfPlopCMS_use_feed' => new sfValidatorBoolean()
    ));

    parent::configure();
  }
}
