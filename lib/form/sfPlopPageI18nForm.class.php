<?php

/**
 * sfPlopPageI18n form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopPageI18nForm extends BasesfPlopPageI18nForm
{
  public function configure()
  {
    parent::configure();

    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('plopAdmin');
    $this->widgetSchema->getFormFormatter()->setHelpFormat(
      sfPlop::get('sf_plop_form_help_format')
    );

    if ($this->getOption('only_title') === true)
    {
      unset(
        $this['subtitle'],
        $this['seo_title'],
        $this['seo_description'],
        $this['seo_keywords']
      );
    }
    else
    {
      $this->validatorSchema['locale'] = new sfValidatorChoice(array(
        'choices' => sfPlop::get('sf_plop_cultures'),
        'required' => false
      ));

      $this->widgetSchema['seo_title']->setLabel('SEO title');
      $this->widgetSchema['seo_description']->setLabel('SEO description');
      $this->widgetSchema['seo_keywords']->setLabel('SEO keywords');

      $this->widgetSchema->setHelps(array(
        'seo_title' => 'If this field is filled, this will override the complete title.'
      ));
    }
  }
}
