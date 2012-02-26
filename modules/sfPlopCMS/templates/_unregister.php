<h2>
  <strong><?php echo __('Unregistration', '', 'plopAdmin') ?></strong>
</h2>

<p><?php echo __('You request your unregistration and we want to be sure this really what you want. Please click the link to confirm your request, or return to the homepage.', '', 'plopAdmin') ?></p>
<ul class="w-list">
  <li>
    <?php echo link_to(
      __('Click this link to confirm your unregistration', '', 'plopAdmin'),
      '@sf_plop_unregister?confirmation=1'
    ) ?>
  </li>
  <li>
    <?php echo __('OR', '', 'plopAdmin') ?>
  </li>
  <li>
    <?php echo link_to(
      __('Return to the homepage', '', 'plopAdmin'),
      '@sf_plop_homepage'
    ) ?>
  </li>
</ul>