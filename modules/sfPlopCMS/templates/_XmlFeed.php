<?php use_helper('I18N', 'jQuery', 'Date') ?>

<?php
if($slot->getOption('url') && $slot->getOption('displays'))
	$xmlFeed = sfFeedPeer::createFromWeb($slot->getOption('url'));
else
	$xmlFeed = false;
?>
<?php if($xmlFeed): ?>
  <ul class="XmlFeed_list">
    <li>
      <strong>
        <?php $title = $xmlFeed->getDescription() ? $xmlFeed->getDescription() : $xmlFeed->getTitle(); ?>
        <?php echo (
          $slot->getOption('truncate_text')
          && $slot->getOption('truncate_type') == 'title'
        ) ?
          sfPlopTools::truncate_txt($title, $slot->getOption('truncate_text'), true)
          : $title
        ; ?>
      </strong>
    </li>
    <?php $i=0; foreach($xmlFeed->getItems() as $post): $i++; ?>
      <li>
        <p>
          <?php if(in_array('element_title', $slot->getOption('displays'))): ?>
            <?php $title = (
              $slot->getOption('truncate_text')
              && $slot->getOption('truncate_type') == 'element_title'
            ) ?
              sfPlopTools::truncate_txt($post->getTitle(), $slot->getOption('truncate_text'), true)
              : $post->getTitle()
            ; ?>
            <strong><?php echo link_to(
              $title,
              $post->getLink(),
              array(
                'class' => 'link',
                'target' => '_blank',
                'title' => $post->getTitle()
            )) ?></strong>
          <?php endif; ?>
          <?php if(in_array('element_description', $slot->getOption('displays'))): ?>
            <br />
            <?php echo (
              $slot->getOption('truncate_text')
              && $slot->getOption('truncate_type') == 'element_description'
            ) ?
              sfPlopTools::truncate_txt($post->getDescription(), $slot->getOption('truncate_text'), true)
              : $post->getDescription()
            ; ?>
          <?php endif; ?>
          <?php if(in_array('element_date', $slot->getOption('displays'))): ?>
            <?php $date = format_date($post->getPubdate(), 'EEEE dd MMMM yyyy') ?>
            <br />
            <em>
              <?php echo (
                $slot->getOption('truncate_text')
                && $slot->getOption('truncate_type') == 'element_date'
              ) ?
                sfPlopTools::truncate_txt($date, $slot->getOption('truncate_text'), true)
                : $date
              ; ?>
            </em>
          <?php endif; ?>
        </p>
      </li>
      <?php if($i == $slot->getOption('limit')) break; ?>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
