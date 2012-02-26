<?php

/**
 * sfPlopThemeEdition form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopThemeEditionForm extends sfPlopConfigForm
{
  public function configure()
  {
    parent::configure();

    $this->embedForm('common', new sfPlopThemeCommonForm());
    $this->embedForm('bg', new sfPlopThemeBackgroundForm());
    $this->embedForm('wrapper', new sfPlopThemeWrapperForm());
    $this->embedForm('block', new sfPlopThemeBlockForm());
    $this->embedForm('text', new sfPlopThemeTextForm());

    $this->widgetSchema->setNameFormat('sfPlopThemeEdition[%s]');
  }
}
