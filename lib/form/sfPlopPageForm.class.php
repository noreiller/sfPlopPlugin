<?php

/**
 * sfPlopPage form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopPageForm extends BasesfPlopPageForm
{
  public function configure()
  {
    parent::configure();

    unset(
      $this['tree_left'],
      $this['tree_right'],
      $this['tree_level'],
      $this['created_at'],
      $this['updated_at']
    );

    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('plopAdmin');
    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->validatorSchema->setOption('filter_extra_fields', false);

    if ($this->isNew())
    {
      if ($this->getOption('culture', sfPlop::get('sf_plop_default_culture')))
        $this->getObject()->setCulture($this->getOption('culture'));

      $this->embedForm($this->getObject()->getCulture(), new sfPlopPageI18nForm(
        $this->getObject()->getTranslation($this->getObject()->getCulture()),
        array('only_title' => true)
      ));
      $this->widgetSchema->moveField($this->getObject()->getCulture(), sfWidgetFormSchema::FIRST);
    }

    $this->widgetSchema['theme'] = new sfWidgetFormPlopChoiceSubTheme(array(
      'add_empty' => true
    ));
    $this->validatorSchema['theme'] = new sfvalidatorPlopChoiceSubTheme(array(
      'required' => false
    ));

    $this->widgetSchema['is_published']->setLabel('Is published ?');
    $this->widgetSchema['is_category']->setLabel('Is category ?');

    $this->widgetSchema['icon'] = new sfWidgetFormAssetExtraChoice(array(
      'label' => 'Icon'
    ));
    $this->validatorSchema['icon'] = new sfValidatorString(array(
      'required' => false
    ));


    if ($this->getOption('page_ref') instanceof sfPlopPage)
    {
      $this->widgetSchema['template_id'] = new sfWidgetFormInputHidden(array(
        'default' => $this->getOption('page_ref')->getTemplateId()
      ));
      $this->widgetSchema['theme'] = new sfWidgetFormInputHidden(array(
        'default' => $this->getOption('page_ref')->getTheme()
      ));
    }
    elseif ($this->getObject()->isRoot() || $this->getObject()->hasSlots())
    {
      unset($this['template_id']);
    }
    else
    {
      $this->widgetSchema['template_id'] = new sfWidgetFormChoice(array(
        'choices' => array('' => '') + sfPlopPagePeer::getPagesWithLevel()
      ));
      $this->validatorSchema['template_id'] = new sfValidatorChoice(array(
        'choices' => array_keys(sfPlopPagePeer::getPagesWithLevel()),
        'required' => false
      ));
    }

    if (!$this->getObject()->isRoot())
    {
      $this->widgetSchema['position'] = new sfWidgetFormChoice(array(
        'choices' => $this->getPositions(),
        'expanded' => true
      ));
      $this->validatorSchema['position'] = new sfValidatorChoice(array(
        'choices' => array_keys($this->getPositions()),
        'required' => false
      ));

      $this->widgetSchema['position_relative'] = new sfWidgetFormChoice(array(
        'label' => 'Relative position to',
        'choices' => array('' => '') + sfPlopPagePeer::getPagesWithLevel()
      ));
      $this->validatorSchema['position_relative'] = new sfValidatorChoice(array(
        'choices' => array_keys(sfPlopPagePeer::getPagesWithLevel()),
        'required' => false
      ));
    }

    $this->validatorSchema->setPostValidator(
      new sfValidatorCallback(array(
        'callback' => array($this, 'checkPositions')
      ))
    );
  }

  /**
   * Returns the position options.
   * @return Array
   */
  protected function getPositions() {
    return array(
      'first_child' => 'as the first child page',
      'last_child' => 'as the last child page',
      'previous_sibling' => 'as the previous page',
      'next_sibling' => 'as the next page'
    );
  }

  /**
   * Check if positions settings are valid.
   * @param sfValidatorBase $validator
   * @param array $values
   * @return array
   */
  public function checkPositions(sfValidatorBase $validator, $values = array())
  {
    if(
      (array_key_exists('position', $values) && $values['position'] != '')
      && (array_key_exists('position_relative', $values) && $values['position_relative'] != '')
    ) {
      $relative_page = sfPlopPagePeer::retrieveByPk($values['position_relative']);
      if (!$relative_page)
      {
        $error =  new sfValidatorError($validator, 'The relative page can\'t be found.');
        throw new sfValidatorErrorSchema($validator, array('position_relative' => $error));
      }

      $root_page = $relative_page->isRoot() ? $relative_page : sfPlopPagePeer::retrieveRoot();

      if ($values['slug'] == $root_page->getSlug())
      {
        $error =  new sfValidatorError($validator, 'The homepage can\'t be moved.');
        throw new sfValidatorErrorSchema($validator, array('position' => $error));
      }
      elseif (
        $relative_page->isRoot()
        && in_array($values['position'], array('previous_sibling', 'next_sibling'))
      )
      {
        $error =  new sfValidatorError($validator, 'The page must be at a lower level than the homepage.');
        throw new sfValidatorErrorSchema($validator, array('position' => $error));
      }

    }

    return $values;
  }

  protected function doSave($con = null)
  {
    $values = $this->values;

    if (isset($values['position']) && $values['position'] != '')
    {
      $relative_page = sfPlopPagePeer::retrieveByPk($values['position_relative']);

      if (in_array($values['position'], array('first_child', 'last_child')))
      {
        if ($this->getObject()->isNew() && $values['position'] == 'first_child')
          $this->getObject()->insertAsFirstChildOf($relative_page);
        elseif ($values['position'] == 'first_child')
          $this->getObject()->moveToFirstChildOf($relative_page);
        elseif ($this->getObject()->isNew())
          $this->getObject()->insertAsLastChildOf($relative_page);
        else
          $this->getObject()->moveToLastChildOf($relative_page);
      }
      elseif (
        in_array($values['position'], array('previous_sibling', 'next_sibling'))
        && ($relative_page && !$relative_page->isRoot())
      )
      {
        if ($this->getObject()->isNew() && $values['position'] == 'previous_sibling')
          $this->getObject()->insertAsPrevSiblingOf($relative_page);
        elseif ($values['position'] == 'previous_sibling')
          $this->getObject()->moveToPrevSiblingOf($relative_page);
        elseif ($this->getObject()->isNew())
          $this->getObject()->insertAsNextSiblingOf($relative_page);
        else
          $this->getObject()->moveToNextSiblingOf($relative_page);
      }
    }
    elseif ($this->getObject()->isNew())
    {
      if ($this->getOption('page_ref') && $this->getOption('page_ref') instanceof sfPlopPage)
        $this->getObject()->insertAsNextSiblingOf($this->getOption('page_ref'));
      else
        $this->getObject()->insertAsLastChildOf(sfPlopPagePeer::retrieveRoot());
    }

    parent::doSave($con);

    if ($this->getOption('page_ref') instanceof sfPlopPage)
    {
      $this->getObject()->copySlotsFrom($this->getOption('page_ref'));
    }

    if (
      isset($values[$this->getObject()->getCulture()])
      && $values[$this->getObject()->getCulture()]['title'] == ''
    )
    {
      $this->getObject()->setCulture($values[$this->getObject()->getCulture()]['locale']);
      $this->getObject()->setTitle('Page');
      $this->getObject()->save();
    }
  }
}
