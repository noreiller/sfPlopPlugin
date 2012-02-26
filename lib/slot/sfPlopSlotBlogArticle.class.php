<?php

class sfPlopSlotBlogArticle extends sfPlopSlotRichText
{
  public function  isContentOptionable() {
    return true;
  }

  public function getFields($slot) {
    return array(
      'publication_date' => new sfWidgetFormPlopDate(array(
        'config' => array(
          'culture' => $slot->getCulture(),
          'date_widget' => new sfWidgetFormi18nDate(array('culture' => $slot->getCulture())),
        ),
        'label' => 'Publication date'
      ),
      array('class' => 'field_auto')),
      'disqus_id' => new sfWidgetFormInputText(array(
        'label' => 'Disqus id'
      )),
      'share_on_twitter' => new sfWidgetFormInputCheckbox(array(
        'value_attribute_value' => true,
        'label' => 'Use Twitter share button ?'
      )),
      'share_on_facebook' => new sfWidgetFormInputCheckbox(array(
        'value_attribute_value' => true,
        'label' => 'Use Facebook share button ?'
      )),
      'value' => new sfWidgetFormPlopRichText(array(
        'label' => 'Content'
      ))
    );
  }

  public function getValidators($slot) {
    return array(
      'publication_date' => new sfValidatorDate(array(
        'required' => false
      )),
      'disqus_id' => new sfValidatorRegex(array(
        'pattern' => '/[A-Za-z0-9-_]*/',
        'required' => false
      )),
      'share_on_twitter' => new sfValidatorBoolean(),
      'share_on_facebook' => new sfValidatorBoolean(),
      'value' => new sfValidatorString(array(
        'required' => false
      ))
    );
  }

  public function getSlotValue($slot, $settings) {
    $value = parent::getSlotValue($slot, $settings);

    return get_partial($slot->getTemplate(), array(
      'slot' => $slot,
      'settings' => $settings,
      'article' => $value
    ));
  }
}
