<?php

/**
 * sfPlopSlotConfigI18n form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopSlotConfigI18nForm extends BasesfPlopSlotConfigI18nForm
{
  public function configure()
  {
    parent::configure();

    unset(
      $this['value'],
      $this['options']
    );

    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('plopAdmin');
    $this->widgetSchema->getFormFormatter()->setHelpFormat(
      sfPlop::get('sf_plop_form_help_format')
    );
    $helps = array();

    $slotConfig = $this->getObject()->getsfPlopSlotConfig();
    $slotConfig->setCulture($this->getObject()->getCulture());
    $slotObject = $slotConfig->getSlot()->getTemplateObject();

    if ($slotObject->isContentEditable() || $slotObject->isContentOptionable())
    {
      $fields = $slotObject->getFields($slotConfig);
      foreach ($fields as $field_name => $field_widget)
      {
        $this->widgetSchema[$field_name] = $field_widget;

        if (
          $field_name == 'value'
          && $slotObject->isContentEditable()
          && $this->getOption('isAjax', false)
        )
          $this->widgetSchema[$field_name]->setHidden(true);

        if ($field_name == 'value')
          $this->setDefault(
            $field_name,
            $slotConfig->getValue($this->getOption('culture'))
          );
        else
          $this->setDefault(
            $field_name,
            $slotConfig->getOption(
              $field_name,
              $field_widget->getOption('default'),
              $this->getOption('culture')
            )
          );
      }

      $this->widgetSchema->setHelps($slotObject->getFieldHelps($slotConfig));

      foreach ($slotObject->getValidators($slotConfig) as $field_name => $field_validator) {
        $this->validatorSchema[$field_name] = $field_validator;
      }

      $this->validatorSchema->setPostValidator(new sfValidatorCallback(array(
        'callback' => array($this, 'dumpOptions'),
        'arguments' => array('slot_fields' => array_keys($fields))
      )));
    }
  }

  /**
   * Dump slot fields but value into the options entry.
   * @param sfValidatorBase $validator
   * @param array $values
   * @param Array $arguments
   * @return Array
   */
  public function dumpOptions(sfValidatorBase $validator, array $values, $arguments)
  {
    $options = array();
    foreach($arguments['slot_fields'] as $field) {
      if ($field != 'value') {
        $options[$field] = $values[$field];
        unset($values[$field]);
      }
    }
    $values['options'] = sfYaml::dump($options);

    return $values;
  }
}
