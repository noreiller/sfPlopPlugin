<h2>
  <strong><?php echo __('Ouhla!') ?></strong>
  <?php echo __('The page you requested does not exist...') ?>
</h2>

<p><?php echo __('Error code : 404 (page not found).') ?></p>
<p><?php echo __('It may be that the link you used is no longer valid') ?><br />
<?php echo __('or that you have typed the address (URL) incorrectly.') ?></p>

<p><?php echo link_to(__('Go back to homepage'), '@sf_plop_homepage', array(
  'class' => 'link',
  'title' => __('Go back to homepage')
)) ?></p>
