<?php

/**
 * sfPlopPage form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopPageAttributesForm extends BasesfPlopPageForm
{
  public function configure()
  {
    parent::configure();

    unset(
      $this['slug'],
      $this['is_published'],
      $this['is_category'],
      $this['color'],
      $this['icon'],
      $this['template_id'],
      $this['position'],
      $this['position_relative'],
      $this['theme'],
      $this['tree_left'],
      $this['tree_right'],
      $this['tree_level'],
      $this['created_at'],
      $this['updated_at']
    );

    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('plopAdmin');

    foreach(sfPlop::get('sf_plop_cultures') as $culture)
      $this->embedForm($culture, new sfPlopPageI18nForm(
        $this->getObject()->getTranslation($culture)));

  }

  protected function doSave($con = null) {
    $values = $this->values;

    parent::doSave($con);

    foreach(sfPlop::get('sf_plop_cultures') as $culture)
      if ($values[$culture]['title'] == '')
      {
        $this->getObject()->setCulture($values[$culture]['locale']);
        $this->getObject()->setTitle('Page');
        $this->getObject()->save();
      }
  }
}
