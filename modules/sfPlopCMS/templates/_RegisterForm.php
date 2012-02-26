<?php if ($sf_user->isAuthenticated()): ?>

  <div class="w-form">
    <h2>
      <strong><?php echo __('Register!') ?></strong>
    </h2>
    <ul class="w-menu">
      <li>
        <span class="element">
          <?php echo __('You are logged as "%u%".', array('%u%' => $sf_user->getUsername())) ?>
        </span>
      </li>
      <li><?php echo link_to(__('Logout'), '@sf_plop_signout', 'class=element') ?></li>
    </ul>
  </div>

<?php else: ?>

  <?php include_partial('sfPlopCMS/register', array(
    'culture' => $settings['culture'],
    'form' => $form
  )) ?>

<?php endif; ?>
