<?php slot('sf_plop_theme', sfPlop::getAdminTheme($sf_user->getProfile()->getTheme(), true, true)) ?>

<?php slot('sf_plop_admin'); ?>
  <?php include_partial('sfPlopDashboard/toolbarDashboard', array(
    'tabs' => $tabs,
    'sub' => $sub,
  )) ?>
<?php end_slot(); ?>


<?php if ($version !== false): ?>
  <<?php echo html5Tag('section'); ?> class="section lcr">
    <div class="content RichText">
      <h1><?php echo __('Informations', '', 'plopAdmin') ?></h1>
      <p><?php echo __(
        'Version number : %s',
        array('%s' => content_tag('strong', $version)),
        'plopAdmin')
      ?></p>
    </div>
  </<?php echo html5Tag('section'); ?>>
<?php endif; ?>

<?php if (sfPlop::get('sf_plop_dashboard_show_welcome_message')): ?>
  <<?php echo html5Tag('section'); ?> class="section lcr">
    <div class="content RichText">
      <h1><?php echo __('Welcome on your Plop-CMS website !', '', 'plopAdmin') ?></h1>
      <p><?php echo __('We invite you to visit the Plop-CMS websites where you\'ll find documentation, help and news about Plop-CMS.', '', 'plopAdmin') ?></p>
      <ul>
        <li><?php echo __('Main website', '', 'plopAdmin') ?> :&nbsp;
          <a href="http://www.plop-cms.com/">www.plop-cms.com</a>
        </li>
        <li><?php echo __('Help and documentation', '', 'plopAdmin') ?> :&nbsp;
          <a href="http://forge.plop-cms.com/">forge.plop-cms.com</a>
        </li>
      </ul>
    </div>
  </<?php echo html5Tag('section'); ?>>
<?php endif; ?>

<?php if (sfPlop::get('sf_plop_dashboard_show_browser_recommandations')): ?>
  <<?php echo html5Tag('section'); ?> class="section lcr">
    <div class="content RichText">
      <h1><?php echo __('Browser recommandations', '', 'plopAdmin') ?></h1>
      <p><?php echo __('You should use a modern browser to administrate your website. Here is a list of compatible browsers.', '', 'plopAdmin') ?></p>
      <ul>
        <li>Mozilla Firefox 4.0+</li>
        <li>Google Chrome 10+</li>
        <li>Opera 11.10+</li>
        <li>Apple Safari 5.0+</li>
        <li>Microsoft Internet Explorer 9+</li>
      </ul>
    </div>
  </<?php echo html5Tag('section'); ?>>
<?php endif; ?>

<?php if(sfPlop::get('sf_plop_dashboard_show_news') && null !== $feed): ?>
  <<?php echo html5Tag('section'); ?> class="section ml">
    <div class="content RichText">
      <h1><?php echo __($feed->getDescription(), '', 'plopAdmin') ?></h1>
      <ul>
        <li>
          <strong><?php echo __($feed->getDescription(), '', 'plopAdmin'); ?></strong>
        </li>
        <?php $i=0; foreach($feed->getItems() as $post): $i++; ?>
          <li>
            <a href="<?php echo $post->getLink() ?>" title="<?php echo $post->getTitle() ?>">
              <?php echo $post->getDescription() ?>
            </a>
          </li>
          <?php if($i == 10) break; ?>
        <?php endforeach; ?>
      </ul>
    </div>
  </<?php echo html5Tag('section'); ?>>
<?php endif; ?>

<?php if(sfPlop::get('sf_plop_dashboard_show_stats')): ?>
  <<?php echo html5Tag('section'); ?> class="section mr">
    <div class="content RichText">
      <h1><?php echo __('Statistics', '', 'plopAdmin') ?></h1>
      <ul>
        <li>
          <?php echo format_number_choice('[0] No page |[1] One page |(1,Inf] %s pages', array('%s' => $nb_pages), $nb_pages, 'plopAdmin') ?>
        </li>
        <li>
          <?php echo format_number_choice('[0] No culture |[1] One culture |(1,Inf] %s cultures', array('%s' => $nb_cultures), $nb_cultures, 'plopAdmin') ?>
        </li>
        <li>
          <?php echo format_number_choice('[0] No media |[1] One media |(1,Inf] %s medias', array('%s' => $nb_medias), $nb_medias, 'plopAdmin') ?>
          <?php if($nb_medias > 0): ?>
            <ul>
              <?php foreach($nb_medias_format as $format => $nb): ?>
                <?php if($nb > 0): ?>
                  <li><?php echo __($format, '', 'plopAdmin') ?> : <?php echo $nb ?></li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </li>
        <li>
          <?php echo format_number_choice('[0] No contact |[1] One contact |(1,Inf] %s contacts', array('%s' => $nb_contacts), $nb_contacts, 'plopAdmin') ?>
        </li>
        <li>
          <?php echo format_number_choice('[0] No user |[1] One user |(1,Inf] %s users', array('%s' => $nb_users), $nb_users, 'plopAdmin') ?>
          <?php if($nb_users > 0): ?>
            <ul>
              <?php foreach($nb_users_statuses as $status => $nb): ?>
                <?php if($nb > 0): ?>
                  <li><?php echo __($status, '', 'plopAdmin') ?> : <?php echo $nb ?></li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </li>
      </ul>
    </div>
  </<?php echo html5Tag('section'); ?>>
<?php endif; ?>

<?php //echo file_get_contents(sfConfig::get('sf_plugins_dir').'/sfPlopPlugin/VERSION') ?>

<?php echo clear(); ?>