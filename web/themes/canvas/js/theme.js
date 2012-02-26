jQuery(document).ready(function () {

  // Main Navigation
  jQuery('body.canvas .MainNavigation .content ul.w-menu .element').bind('click', function () {
    if (jQuery(this).next().is('ul:hidden')) {
      jQuery(this)
        .parents('ul.w-menu:first')
        .find('ul')
        .slideUp('slow')
      ;

      jQuery(this).next()
        .slideDown('slow')
      ;
    }
    else if (jQuery(this).next().is('ul:visible')) {
      jQuery(this).next()
        .slideUp('slow')
      ;
    }
  });
  jQuery('body.canvas .MainNavigation .content ul.w-menu .element + ul > li.current')
    .parents('ul:first')
    .slideDown('slow')
  ;

});
