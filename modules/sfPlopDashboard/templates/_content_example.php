
<<?php echo html5Tag('section'); ?> class="section lcr PageHeader">
  <div class="w-block content RichText">
    <h1><a href="#">Lorem Ipsum - dolor sit amet</a></h1>
  </div>
</<?php echo html5Tag('section'); ?>>

<<?php echo html5Tag('section'); ?> class="section l MainNavigation">
  <div class="w-block content">

    <ul class="w-menu ">
      <li class="current lvl">
        <a class="element" href="#"><strong>Lorem</strong></a>
      </li>
      <li class=" lvl1">
        <a class="element" href="#"><strong>Ipsum</strong></a>
      </li>
      <li class=" lvl1">
        <a class="element" href="#"><strong>Dolor</strong></a>
        <ul>
          <li class=" lvl2">
            <a class="element" href="#"><strong>Sit</strong></a>
          </li>
          <li class=" lvl2">
            <a class="element" href="#"><strong>Amet</strong></a>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</<?php echo html5Tag('section'); ?>>

<<?php echo html5Tag('section'); ?> class="section cr PageTitle">
  <div class="w-block content RichText">
    <h1>Lorem Ipsum</h1>
  </div>
</<?php echo html5Tag('section'); ?>>

<<?php echo html5Tag('section'); ?> class="section cr RichText">
  <div class="w-block content RichText">
    <p><a href="#"><img src="/sfPlopPlugin/images/plop-big-black-fr.png" style="float: left;"></a> <strong>Pellentesque habitant morbi tristique</strong> senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. <em>Aenean ultricies mi vitae est.</em> Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, <code>commodo vitae</code>, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. <a href="#">Donec non enim</a> in turpis pulvinar facilisis. Ut felis.</p>

    <h2>Header Level 2</h2>
    <ol>
       <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
       <li>Aliquam tincidunt mauris eu risus.</li>
    </ol>
    <blockquote><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at felis aliquet congue. Ut a est eget ligula molestie gravida. Curabitur massa. Donec eleifend, libero at sagittis mollis, tellus est malesuada tellus, at luctus turpis elit sit amet quam. Vivamus pretium ornare est.</p></blockquote>
    <h3>Header Level 3</h3>
    <ul>
      <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
      <li>Aliquam tincidunt mauris eu risus.</li>
    </ul>
    <pre><code>
    .header h1 a {
      display: block;
      width: 300px;
      height: 80px;
    }
    </code></pre>
  </div>
</<?php echo html5Tag('section'); ?>>

<<?php echo html5Tag('section'); ?> class="section r pageFooter">
  <div class="w-block content">
    <a href="http://www.plop-cms.com" class="w-powered-button"
     title="<?php echo __('Powered by %s', array('%s' => 'Plop CMS')) ?>">
      <?php echo __('Powered by %s', array(
        '%s' => content_tag('em', 'Plop CMS')
      )) ?>
    </a>
  </div>
</<?php echo html5Tag('section'); ?>>

<?php echo clear(); ?>
