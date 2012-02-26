<h2>
  <strong><?php echo __('Ouhla!') ?></strong>
  <?php echo __('The page you requested is protected...') ?>
</h2>

<p><?php echo __('Error code : 403 (forbidden).') ?></p>
<p><?php echo __('It seems that you have not the required credentials to access this page') ?><br />
<?php echo __('or that you have typed the address (URL) incorrectly.') ?></p>

<p><?php echo link_to(__('Go back to homepage'), '@sf_plop_homepage', array(
  'class' => 'link',
  'title' => __('Go back to homepage')
)) ?></p>
