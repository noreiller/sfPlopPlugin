<?php if (!isset($page)) $page = null; ?>

<<?php echo html5Tag('nav'); ?> class="nav">
  <?php if (isset($page)): ?>
    <?php if (!isset($type)) $type = null; ?>
    <?php $root = sfPlopPageQuery::create()->findRoot(); ?>
    <ul class="w-menu menu-custom">
      <li class="w-menu-dd w-menu-dd-left">
        <span class="element">
          <?php echo __('Page : %s', array(
            '%s' => content_tag('strong', $page->getTitle(), 'class=' . ($page->getIsPublished() ? 'published' : 'unpublished'))
          ), 'plopAdmin') ?>
          <?php echo image_flag($culture, array('alt' => format_language($culture))) ?>
          <?php if ($page->isTemplate()): ?>
            <?php echo content_tag('span', '(' . $page->countTemplateChildren() . ')', array(
                'title' => __('This page has %s template children.', array('%s' => $page->countTemplateChildren()), 'plopAdmin'),
                'class' => 'w'
              )) ?>
          <?php endif; ?>
          <?php echo widgetIndicator($page->isTemplate(), 'edit',
            __('This indicates if the page is a template (lock icon).', '', 'plopAdmin'),
            array('rel' => $page->getSlug())
          ) ?>
          <?php echo widgetIndicator($page->isPublished(), 'publish',
            __('This indicates if the page is published (green tick) or not (red bullet).', '', 'plopAdmin'),
            array('rel' => $page->getSlug())
          ) ?>
        </span>
        <ul>
          <?php $nodes = $root->getBranch(); ?>
          <?php foreach($nodes as $page_pk): ?>
            <li class="<?php echo $page_pk->getIsPublished() ? 'published' : 'unpublished'; ?>">
              <?php foreach(sfPlop::get('sf_plop_cultures') as $localization): ?>
                <?php echo link_to(
                  image_flag($localization, array('alt' => format_language($localization))),
                  '@sf_plop_page_show?slug=' . $page_pk->getSlug() . '&sf_culture=' . $localization,
                  array(
                    'class' => 'element flag w-img-link',
                    'title' => $page_pk->getSlug()
                )) ?>
              <?php endforeach; ?>
              <?php echo link_to_unless(
                $page->getSlug() == $page_pk->getSlug(),
                str_repeat('-', $page_pk->getLevel()) . ' ' . $page_pk->getSlug(),
                '@sf_plop_page_show?sf_culture=' . $culture . '&slug=' . $page_pk->getSlug(),
                array(
                  'class' => 'element',
                  'title' => $page_pk->getSlug()
              )) ?>
              <?php echo widgetIndicator($page_pk->isTemplate(), 'edit',
                __('This indicates if the page is a template (lock icon).', '', 'plopAdmin'),
                array('rel' => $page_pk->getSlug())
              ) ?>
              <?php echo widgetIndicator($page_pk->isPublished(), 'publish',
                __('This indicates if the page is published (green tick) or not (red bullet).', '', 'plopAdmin'),
                array('rel' => $page_pk->getSlug())
              ) ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </li>
      <li class="w-menu-dd">
        <span class="element w w-edition"><?php echo __('Edition', '', 'plopAdmin') ?></span>
        <ul>
          <li>
            <?php echo link_to(
              __('Page properties', '', 'plopAdmin'),
              '@sf_plop_page_edit?sf_culture=' . $culture . '&slug=' . $page->getSlug(),
              array(
                'class' => 'element w-ajax w-ajax-d',
                'title' => __('Page properties', '', 'plopAdmin')
            )) ?>
          </li>
          <li>
            <?php echo link_to(
              __('Page attributes', '', 'plopAdmin'),
              '@sf_plop_page_edit_attributes?sf_culture=' . $culture . '&slug=' . $page->getSlug(),
              array(
                'class' => 'element w-ajax w-ajax-d',
                'title' => __('Page attributes', '', 'plopAdmin')
            )) ?>
          </li>
          <?php if (!$page->isRoot()): ?>
            <li>
              <?php if ($page->isPublished()): ?>
                <?php $label = 'Unpublish'; ?>
              <?php else: ?>
                <?php $label = 'Publish'; ?>
              <?php endif; ?>
              <?php echo link_to(
                __($label, '', 'plopAdmin'),
                '@sf_plop_page_toggle_publication?sf_culture=' . $culture . '&slug=' . $page->getSlug(),
                array(
                  'class' => 'element w-confirm',
                  'rel' => $page->getSlug(),
                  'title' => __($label, '', 'plopAdmin')
              )) ?>
            </li>
            <?php if (!$page->isPublished() && !$page->hasTemplateChildren() && !$page->hasChildren()): ?>
              <li>
                <?php echo link_to(
                  __('Delete page', '', 'plopAdmin'),
                  '@sf_plop_page_delete?sf_culture=' . $culture . '&slug=' . $page->getSlug(),
                  array(
                    'class' => 'element w-confirm',
                    'title' => __('Delete page', '', 'plopAdmin')
                )) ?>
              </li>
            <?php endif; ?>
          <?php endif; ?>
          <?php if (!$page->isTemplate()): ?>
            <?php $pageTemplate = $page->getTemplate() ?>
            <li>
              <?php echo link_to(
                __('Go to template page', '', 'plopAdmin'),
                '@sf_plop_page_show?sf_culture=' . $culture . '&slug=' . $pageTemplate->getSlug(),
                array(
                  'class' => 'element',
                  'title' => __('Go to template page', '', 'plopAdmin')
              )) ?>
            </li>
          <?php endif; ?>
          <li>
            <?php echo link_to(
              __('Duplicate this page', '', 'plopAdmin'),
              '@sf_plop_page_copy?sf_culture=' . $culture . '&slug=' . $page->getSlug(),
              array(
                'class' => 'element w-ajax w-ajax-d',
                'title' => __('Duplicate this page', '', 'plopAdmin')
            )) ?>
          </li>
          <li class="separator">
            <?php echo link_to(
              __('Create a new page', '', 'plopAdmin'),
              '@sf_plop_page_create?sf_culture=' . $culture,
              array(
                'class' => 'element w-ajax w-ajax-d',
                'title' => __('Create a new page', '', 'plopAdmin')
            )) ?>
          </li>
          <?php if ($page->isSlotable()): ?>
            <li>
              <?php echo link_to(
                __('Create a new block', '', 'plopAdmin'),
                '@sf_plop_page_create_slot?sf_culture=' . $culture . '&slug=' . $page->getSlug(),
                array(
                  'class' => 'element w-ajax w-ajax-d',
                  'title' => __('Create a new block', '', 'plopAdmin')
              )) ?>
            </li>
          <?php endif; ?>
        </ul>
      </li>
      <li>
        <?php include_partial('sfPlopCMS/widget_preview') ?>
      </li>
      <li>
        <?php include_partial('sfPlopCMS/widget_theme', array('culture' => $culture)) ?>
      </li>
    </ul>
  <?php endif; ?>

  <?php include_partial('sfPlopCMS/toolbar_quick_links') ?>
</<?php echo html5Tag('nav'); ?>>