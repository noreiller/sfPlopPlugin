<?php if ($feed === false): ?>
  <p><?php echo __('The feed can\'t be displayed.') ?></p>
<?php else: ?>
  <ul class="w-list">
    <li>
      <strong>
        <?php echo ($truncate_text && $truncate_type == 'title')
          ? truncate_text($feed['title'], $truncate_text, '...', true)
          : $feed['title']
        ; ?>
      </strong>
    </li>
    <?php foreach ($feed['items'] as $item): ?>
      <li>
        <?php if (in_array('element_title', $displays)): ?>
          <?php $title = ($truncate_text && $truncate_type == 'element_title') 
            ? truncate_text($item['title'], $truncate_text, '...', true)
            : $item['title']
          ; ?>
          <strong><?php echo link_to($title, $item['url'], array(
            'class' => 'link',
            'target' => '_blank',
            'title' => $item['title']
          )) ?></strong>
        <?php endif; ?>
        <?php if (in_array('element_description', $displays)): ?>
          <br />
          <?php echo ($truncate_text && $truncate_type == 'element_description') 
            ? truncate_text($item['description'], $truncate_text, '...', true)
            : $item['description']
          ; ?>
        <?php endif; ?>
        <?php if (in_array('element_date', $displays)): ?>
          <?php $date = format_date($item['date'], 'EEEE dd MMMM yyyy') ?>
          <br />
          <em>
            <?php echo ($truncate_text && $truncate_type == 'element_date') 
              ? truncate_text($date, $truncate_text, '...', true)
              : $date
            ; ?>
          </em>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
