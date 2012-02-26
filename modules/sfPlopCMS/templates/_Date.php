<?php if ($use_date): ?>
  <span class="date"><?php echo format_date(date('Y-m-d'), 'EEEE dd MMMM yyyy', $settings['culture']); ?></span>
<?php endif; ?>

<?php if ($use_clock): ?>
  <span id="clock_time_<?php echo $slot->getId() ?>" class="clock"
    data-hour="<?php echo date('H'); ?>"
    data-minute="<?php echo date('i'); ?>"
    data-second="<?php echo date('s'); ?>"
  ><?php echo date('H:i:s'); ?></span>
<?php endif; ?>