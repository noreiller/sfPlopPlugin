<?php if (is_array($gallery)): ?>

  <div class="w-gallery  <?php if ($feed_type) echo $feed_type; ?>
    <?php if ($autoplay): ?>autoplay<?php endif; ?>"
  >
    <div class="w-gallery-contents">
      <?php foreach ($gallery['elements'] as $element): ?>
        <div class="w-gallery-content">
					<div class="w-gallery-content-img">
						<?php echo $element[$gallery['keys']['content']]; ?>
          </div>
          <?php if ($legend != 'hidden'): ?>
            <div class="w-gallery-content-legend">
              <?php echo $element[$gallery['keys'][$legend]]; ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
    <?php if ($use_prev_next_buttons): ?>
      <a href="#" class="w-gallery-prev">&#9668;</a>
      <a href="#" class="w-gallery-next">&#9658;</a>
    <?php endif; ?>
    <?php if ($pagination == 'numbers'): ?>
      <ul class="w-menu w-gallery-menu">
        <?php for ($i = 0; $i < $gallery['count']; $i++): ?>
          <li><a href="#<?php echo $i; ?>"><?php echo $i+1; ?></a></li>
        <?php endfor; ?>
      </ul>
    <?php endif; ?>
  </div>

<?php endif; ?>