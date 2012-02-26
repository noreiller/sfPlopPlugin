<?php

/**
 * sfPlopSlotConfig form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopSlotConfigForm extends BasesfPlopSlotConfigForm
{
  public function configure()
  {
    parent::configure();

    unset(
      $this['created_at'],
      $this['updated_at'],
      $this['slot_id'],
      $this['page_id']
    );

    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('plopAdmin');
    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->validatorSchema->setOption('filter_extra_fields', false);

    $this->embedForm('i18n', new sfPlopSlotConfigI18nForm(
      $this->getObject()->getTranslation($this->getObject()->getCulture()),
      array(
        'isAjax' => $this->getOption('isAjax', false),
        'culture' => $this->getOption('culture')
      )
    ));
  }
}
