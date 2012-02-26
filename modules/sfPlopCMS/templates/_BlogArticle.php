<?php $page = $settings['page']; ?>

<?php echo $article ?>

<?php
$pub_date = $slot->getOption('publication_date');
$date = isset($pub_date) ? $pub_date : $slot->getCreatedAt();
?>
<span><?php echo format_date($date, 'EEEE dd MMMM yyyy', $slot->getCulture()) ?></span> -
<a href="<?php echo sfPlopTools::urlForPage($page->getSlug(), '', $slot->getCulture()) ?>#disqus_thread"><?php echo __('Comments') ?></a>

<?php if($slot->getOption('share_on_twitter')): ?>
  <span class="button-to-share">
    <a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" >Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
  </span>
<?php endif; ?>

<?php if($slot->getOption('share_on_facebook')): ?>
  <span class="button-to-share">
    <iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode(sfPlopTools::urlForPage($page->getSlug(), '', $slot->getCulture())) ?>&amp;layout=button_count&amp;show_faces=true&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:150px; height:21px;" allowTransparency="true"></iframe>
  </span>
<?php endif; ?>

<?php if($slot->getOption('disqus_id')): ?>
  <div id="disqus_thread"></div>
  <script type="text/javascript">
    var disqus_shortname = '<?php echo $slot->getOption('disqus_id') ?>';
    var disqus_identifier = '<?php echo $slot->getCulture() . '-' . $page->getSlug() ?>';
    var disqus_url = '<?php echo sfPlopTools::urlForPage($page->getSlug(), '', $slot->getCulture()) ?>';
    (function() {
     var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
     dsq.src = 'http://<?php echo $slot->getOption('disqus_id') ?>.disqus.com/embed.js';
     (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    })();
    (function () {
      var s = document.createElement('script'); s.async = true;
      s.src = 'http://disqus.com/forums/<?php echo $slot->getOption('disqus_id') ?>/count.js';
      (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
    }());
  </script>
  <noscript><?php echo __('Please enable JavaScript to view the') ?> <a href="http://disqus.com/?ref_noscript=<?php echo $slot->getOption('disqus_id') ?>"><?php echo __('comments powered by') ?> Disqus.</a></noscript>
  <a href="http://disqus.com" class="dsq-brlink"><?php echo __('Blog comments powered by') ?> <span class="logo-disqus">Disqus</span></a>
<?php endif; ?>
  