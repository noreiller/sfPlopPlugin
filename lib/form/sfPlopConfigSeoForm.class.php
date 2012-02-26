<?php

/**
 * sfPlopConfig form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopConfigSeoForm extends sfPlopConfigForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'sf_plop_website_description' => new sfWidgetFormTextarea(array(
        'default' => sfPlop::get('sf_plop_website_description'),
        'label' => 'SEO description',
      )),
      'sf_plop_website_keywords' => new sfWidgetFormInputText(array(
        'label' => 'SEO keywords',
      ), array(
        'value' => sfPlop::get('sf_plop_website_keywords')
      )),
      'sf_plop_use_custom_page_title' => new sfWidgetFormInputCheckbox(array(
        'label' => 'Use the website title in the page title ?',
      ), array(
        'checked' => ((sfPlop::get('sf_plop_use_custom_page_title') == true) ? 'checked' : null),
      )),
      'sf_plop_website_title_position' => new sfWidgetFormSelect(array(
        'choices' => array('before' => 'before', 'after' => 'after'),
        'label' => 'Website title position'
      ),
      array()),
      'sf_plop_website_title' => new sfWidgetFormInputText(array(
        'label' => 'Website title',
      ), array(
        'value' => sfPlop::get('sf_plop_website_title')
      )),
      'sf_plop_website_title_prefix' => new sfWidgetFormInputText(array(
        'label' => 'Website title prefix',
      ), array(
        'value' => sfPlop::get('sf_plop_website_title_prefix')
      )),
      'sf_plop_website_title_suffix' => new sfWidgetFormInputText(array(
        'label' => 'Website title suffix',
      ), array(
        'value' => sfPlop::get('sf_plop_website_title_suffix')
      )),
      'sf_plop_use_title_in_seo_description' => new sfWidgetFormInputCheckbox(array(
        'label' => 'Use the page title in the description ?',
      ), array(
        'checked' => ((sfPlop::get('sf_plop_use_title_in_seo_description') == true) ? 'checked' : null),
      ))
    ));

    $this->setDefault('sf_plop_website_title_position', sfPlop::get('sf_plop_website_title_position'));

    $this->setValidators(array(
      'sf_plop_website_description' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_website_keywords' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_use_custom_page_title' => new sfValidatorBoolean(array(
        'required' => false
      )),
      'sf_plop_website_title_position' => new sfValidatorPass(),
      'sf_plop_website_title' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_website_title_prefix' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_website_title_suffix' => new sfValidatorString(array(
        'required' => false
      )),
      'sf_plop_use_title_in_seo_description' => new sfValidatorBoolean(array(
        'required' => false
      ))
    ));

    parent::configure();
  }
}
