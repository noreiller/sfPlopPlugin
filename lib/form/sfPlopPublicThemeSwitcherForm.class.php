<?php
class sfPlopPublicThemeSwitcherForm extends sfForm
{
  public function configure()
  {
    $this->disableCSRFProtection();
    
    $this->setWidgets(array(
      'theme' => new sfWidgetFormPlopChoiceTheme()
    ));
    $this->setValidators(array(
      'theme' => new sfValidatorPlopChoiceTheme()
    ));

    $this->widgetSchema->setDefaults(array(
       'theme' => $this->getOption('theme')
    ));

    $this->widgetSchema->setNameFormat('theme[%s]');
  }
}
?>
