<?php

/**
 * sfPlopConfig form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopConfigForm extends sfForm
{
  public function configure() {
    $this->widgetSchema->setNameFormat('sfPlopConfig[%s]');
    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('plopAdmin');
    $this->widgetSchema->getFormFormatter()->setHelpFormat(
      sfPlop::get('sf_plop_form_help_format')
    );
  }

  /**
   * Get the cleaned values and launch the save.
   */
  public function save()
  {
    $this->doSave($this->getValues());
  }

  /**
   * Save recursively the values.
   * @param Array $values
   */
  public function doSave($values)
  {
    foreach ($values as $field => $value)
    {
      if (isset($this->embeddedForms[$field]))
        $this->doSave($value);
      else
        sfPlopConfigPeer::addOrUpdate($field, $value);
    }
  }
}
