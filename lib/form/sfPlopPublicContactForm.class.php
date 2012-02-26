<?php
class sfPlopPublicContactForm extends sfForm
{
  public function configure() {
    $contact_options = array(
      'label' => 'Your recipient'
    );

    $widgets = array();
    $validators = array();
    $choices = array();

    $contacts = sfGuardUserProfileQuery::create()
      ->findById($this->getOption('contact', array()));

    foreach($contacts as $contact)
      if ($contact->isPublic())
        $choices[$contact->getId()] = $contact->getNameWithRole();

    if (count($choices) > 0)
    {
      $widgets['receiver'] = new sfWidgetFormChoice(array(
        'label' => 'Your recipient',
        'choices' => $choices
      ));
      $validators['receiver'] = new sfValidatorChoice(array(
        'choices' => array_keys($choices)
      ));
    }

    $widgets = array_merge($widgets, array(
      'name' => new sfWidgetFormInputText(array(
        'label' => 'Your name'
      )),
      'email' => new sfWidgetFormInputText(array(
        'label' => 'Your email'
      )),
      'message' => new sfWidgetFormTextarea(array(
        'label' => 'Your message'
      )),
      'copy' => new sfWidgetFormInputCheckbox(array(
        'value_attribute_value' => true,
        'label' => 'Receive a copy of the message by email ?'
      ))
    ));

    $validators = array_merge($validators, array(
      'name' => new sfValidatorString(),
      'email' => new sfValidatorEmail(),
      'message' => new sfValidatorString(),
      'copy' => new sfValidatorBoolean()
    ));

    $this->setWidgets($widgets);
    $this->setValidators($validators);

    $this->widgetSchema->setNameFormat('contact[%s]');
  }
}
?>
