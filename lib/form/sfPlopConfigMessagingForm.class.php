<?php

/**
 * sfPlopConfig form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopConfigMessagingForm extends sfPlopConfigForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'sf_plop_messaging_from_name' => new sfWidgetFormInputText(array(
        'label' => 'Sender name',
      ),
      array(
        'value' => sfPlop::get('sf_plop_messaging_from_name')
      )),
      'sf_plop_messaging_from_email' => new sfWidgetFormInputText(array(
        'label' => 'Sender email',
      ),
      array(
        'value' => sfPlop::get('sf_plop_messaging_from_email')
      )),
      'sf_plop_messaging_to_name' => new sfWidgetFormInputText(array(
        'label' => 'Receiver name',
      ),
      array(
        'value' => sfPlop::get('sf_plop_messaging_to_name')
      )),
      'sf_plop_messaging_to_email' => new sfWidgetFormInputText(array(
        'label' => 'Receiver email',
      ),
      array(
        'value' => sfPlop::get('sf_plop_messaging_to_email')
      )),
      'sf_plop_messaging_subject' => new sfWidgetFormInputText(array(
        'label' => 'Message subject',
      ),
        array(
        'value' => sfPlop::get('sf_plop_messaging_subject')
      )),
      'sf_plop_messaging_message' => new sfWidgetFormTextarea(array(
        'default' => sfPlop::get('sf_plop_messaging_message'),
        'label' => 'Message text'
      )),
    ));
    
    $this->setValidators(array(
      'sf_plop_messaging_from_name' => new sfValidatorString(array('required' => false)),
      'sf_plop_messaging_from_email' => new sfValidatorEmail(array('required' => false)),
      'sf_plop_messaging_to_name' => new sfValidatorString(array('required' => false)),
      'sf_plop_messaging_to_email' => new sfValidatorEmail(array('required' => false)),
      'sf_plop_messaging_subject' => new sfValidatorString(array('required' => false)),
      'sf_plop_messaging_message' => new sfValidatorString(array('required' => false)),
    ));

    parent::configure();
  }
}
