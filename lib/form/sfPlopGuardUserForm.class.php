<?php

/**
 * Description of sfPlopGuardUserForm
 *
 * @author Aurélien MANCA <aurelien.manca@gmail.com>
 */
class sfPlopGuardUserForm extends sfGuardUserForm 
{
  public function configure()
  {
    parent::configure();
    
    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('plopAdmin');
    
    $this->widgetSchema->getFormFormatter()->setHelpFormat(
      sfPlop::get('sf_plop_form_help_format')
    );
  }
}

?>
