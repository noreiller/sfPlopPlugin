<ul class="w-menu">
  <?php if ($sf_user->isAuthenticated()): ?>
    <li>
      <span class="element">
        <?php echo __('You are logged as "%u%".', array('%u%' => $sf_user->getUsername())) ?>
      </span>
    </li>
    <li><?php echo link_to(__('Logout'), '@sf_plop_signout', 'class=element') ?></li>
  <?php else: ?>
    <li><?php echo link_to(__('Login'), '@sf_plop_signin', 'class=element') ?></li>
    <?php if (sfPlop::get('sf_plop_allow_registration')): ?>
      <li><?php echo link_to(__('Register'), '@sf_plop_register', 'class=element') ?></li>
    <?php endif; ?>
  <?php endif; ?>
</ul>