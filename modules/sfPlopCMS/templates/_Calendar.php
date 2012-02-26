<?php use_helper('I18N', 'jQuery', 'Date') ?>

<?php
$culture = $settings['culture'];
$today = date('Y-m-d');
$page_slug = $slot->getPage()->getSlug();
$page_date = is_numeric(str_replace('-', '', substr($page_slug,0,10))) ? substr($page_slug,0,10) : $today;

$dt = new sfPlopDate($page_date);
$c = new sfEventCalendar('month', $page_date);

$current_month = explode('-', $dt->dump());
$prev_month = substr($dt->reset()->subtractMonth()->dump(),0,10);
$next_month = substr($dt->reset()->addMonth()->dump(),0,10);
$page_month = explode('-', $page_date);

// search for events
$events = sfPlopTools::getCalendarPages($culture);
if($events)
{
  foreach($events as $calendar_event)
  {
    if($calendar_event->getIsPublished())
    {
      $c->addEvent(
        substr($calendar_event->getSlug(),0,10),
        array(
          'title' => $calendar_event->getTitle($culture),
          'url' => sfPlopTools::urlForPage($calendar_event->getSlug(), '', $culture)
      ));
    }
  }
}
$calendar = $c->getEventCalendar();
?>

<table class="calendar_table">
    <tr>
      <th class="th"><?php echo jq_link_to_remote(
        '&#9668;',
        array(
          'update' => 'inner_content_'.$slot->getId(),
          'url' => '@plop-cms-module-calendar',
          'with' => "'slug=".$page_slug."&slot_id=".$slot->getId()."&sf_culture=".$culture."&date=".$prev_month."'"
        ), array(
          'class' => 'link',
          'title' => __('Previous month')
      )) ?></th>
      <th class="th" colspan="5"><?php echo format_date($page_date, 'MMMM yyyy', $culture) ?></th>
      <th class="th"><?php echo jq_link_to_remote(
        '&#9658;',
        array(
          'update' => 'inner_content_'.$slot->getId(),
          'url' => '@plop-cms-module-calendar',
          'with' => "'slug=".$page_slug."&slot_id=".$slot->getId()."&sf_culture=".$culture."&date=".$next_month."'"
        ), array(
          'class' => 'link',
          'title' => __('Next month')
      )) ?></th>
    </tr>
  <?php $i=0; foreach ($calendar as $week): ?>
    <tr>
      <?php foreach ($week as $day => $events): $month = explode('-', $day); ?>

      <td class="td <?php echo (in_array(date('N', strtotime($day)), array('6','7'))) ? 'weekend' : 'week' ?> <?php echo ($i == '0') ? 'first-line' : '' ?>">
        <div class="<?php echo ($day == $today) ? 'today' : (($day == $page_date) ? ' current' : ' default') ?>">
          <?php if(!empty($events) && ($month[1] == $page_month[1])): ?>
            <?php foreach ($events as $event): ?>
              <p><?php echo link_to_if(
                isset($event['url']),
                date('d', strtotime($day)),
                $event['url'],
                array(
                  'title' => $event['title'],
                  'class' => 'link'
              )) ?></p>
            <?php endforeach; ?>
          <?php elseif($month[1] == $page_month[1]): ?>
            <p><?php echo date('d', strtotime($day)) ?></p>
          <?php endif; ?>
        </div>
      </td>

      <?php endforeach; ?>
    </tr>
  <?php $i++; endforeach; ?>
</table>