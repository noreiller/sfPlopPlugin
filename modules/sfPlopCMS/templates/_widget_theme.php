<?php echo link_to(
  __('Edit theme', '', 'plopAdmin'),
  '@sf_plop_theme_edit?sf_culture=' . $culture,
  array(
    'class' => 'element w-ajax w-ajax-d w-button w-theme-editor',
    'title' => __('Edit theme', '', 'plopAdmin')
)) ?>