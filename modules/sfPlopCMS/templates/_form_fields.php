<?php $use_legend = isset($use_legend) ? $use_legend : true; ?>
<?php $legend = isset($legend) && $use_legend !== false ? $legend : null; ?>
<?php if ($form instanceOf sfForm): ?>
  <?php echo $form->renderGlobalErrors(); ?>
<?php endif; ?>

<fieldset>

  <?php if ($legend): ?>
    <legend><?php echo __($legend, '', 'plopAdmin') ?> :</legend>
  <?php endif; ?>

  <?php foreach($form as $label => $field): ?>

    <?php if ($field instanceOf sfFormFieldSchema): ?>

      </fieldset>
      <?php include_partial('sfPlopCMS/form_fields', array(
        'form' => $field,
        'legend' => $label,
        'use_legend' => $use_legend
      )) ?>
      <fieldset>

    <?php elseif ($field->isHidden()): ?>

      <?php echo $field->render(array('class' => 'w-form-hidden')); ?>

    <?php elseif (in_array($field->getWidget()->getOption('type'), array('checkbox', 'radio'))): ?>

      <div class="form-row form-row-choice">
        <?php echo $field->renderError()
          . $field->render()
          . $field->renderLabel()
          . $field->renderHelp()
        ; ?>
      </div>

    <?php else: ?>

      <?php
        if ($field->getWidget() instanceof sfWidgetFormTextarea)
          $type = 'textarea';
        elseif ($field->getWidget() instanceof sfWidgetFormChoice)
          $type = 'select';
        else
          $type = 'text';
      ?>

      <div class="form-row form-row-<?php echo $type; ?>">
        <?php echo $field->renderError()
          . $field->renderLabel()
          . $field->render()
          . $field->renderHelp()
        ; ?>
      </div>

    <?php endif; ?>
  <?php endforeach; ?>
</fieldset>

<?php if ($form instanceOf sfForm): ?>
  <?php include_stylesheets_for_form($form) ?>
  <?php include_javascripts_for_form($form) ?>
<?php endif; ?>
