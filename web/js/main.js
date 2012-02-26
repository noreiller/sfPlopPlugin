var sfPlop = {
  /**
   * Properties
   */
  _expose : null,
  _tpplugins: [],

  /**
   * Init now.
   */
  init : function  () {
  },

  /**
   * Init on DOM ready.
   */
  initOnDomReady : function  () {
    this.loadLoader();
    this.loadPlugins();
    this.loadAjaxNavigation();
  },

  /**
   * Register a third-party plugin callback to execute with loadPlugins().
   */
  registerTPPlugin : function (callback) {
    this._tpplugins.push(callback);
  },

  /**
   * Load third-party plugins.
   */
  loadTPPlugins : function () {
    if (this._tpplugins.length > 0) {
      for (var p in this._tpplugins) {
        var callback = this._tpplugins[p];
        if (!isNaN(p) && callback && typeof(callback) === "function")
          callback.call();
      }
    }
  },

  /**
   * Load plugins.
   */
  loadPlugins : function () {
    this.loadGallery();
    this.loadImageZoom();
    this.loadSlider();
    this.loadClock();
    this.loadThemeSwitcher();
    this.loadTPPlugins();
  },

  /**
   * Load the loader plugin
   */
  loadLoader : function () {
    jQuery.prettyLoader({
        animation_speed: 'fast', /* fast/normal/slow/integer (default: 'fast') */
        bind_to_ajax: true, /* true/false (default: true) */
        delay: false, /* false OR time in milliseconds (ms) (default: false) */
        loader: '/sfPlopPlugin/vendor/prettyLoader/images/ajax-loader.gif', /* Path to your loader gif */
        offset_top: 13, /* integer (default: 13) */
        offset_left: 10 /* integer (default: 10) */
      });
  },

  /**
   * Display the loader element.
   */
  showLoader: function () {
    return jQuery.prettyLoader.show();
  },

  /**
   * Hide the loader element.
   */
  hideLoader: function () {
    return jQuery.prettyLoader.hide();
  },

  /*
   * Expose an overlay with the jQuery UI dialog API
   */
  expose: function (el, animate) {
    if (animate == undefined)
      animate = false;

    if (this._expose == null) {
      this._expose = jQuery('<div></div>')
        .addClass('ui-widget-overlay')
        .appendTo(document.body)
        .css({opacity: 0})
      ;
      if (animate)
        this._expose.animate({
          opacity: 1,
          width: jQuery.ui.dialog.overlay.width(),
          height: jQuery.ui.dialog.overlay.height()
        });
      else
        this._expose.css({
          width: jQuery.ui.dialog.overlay.width(),
          height: jQuery.ui.dialog.overlay.height()
        });
    }
    jQuery(window).bind('resize.dialog-overlay', function (e) {
      sfPlop._expose.css({
        width: 0,
        height: 0
      }).css({
        height : jQuery.ui.dialog.overlay.height(),
        width : jQuery.ui.dialog.overlay.width(),
        zIndex : 1002
      });
    });
    if (el != undefined && jQuery(el).length > 0)
      el
        .addClass('w-expose')
        .css({zIndex : 1003})
      ;
      if (animate)
        el.animate({opacity: 1});
  },

  /*
   * Unexpose the previously exposed overlay
   */
  unexpose: function (e) {
    if (this._expose != null) {
      jQuery(window).unbind('resize.dialog-overlay');
      this._expose.remove();
      delete this._expose;
    }

    if (e != undefined && jQuery(e).length > 0)
      e
        .removeClass('w-expose')
        .css({zIndex : 'auto'})
      ;
  },

  /**
   * Ajax Navigation
   * @todo :
   *  * Use HTML5 history api
   *  * Use fade effect or not
   *  * Decide if all internal links should be binded
   *  * Classes from the body, such as .root, should be updated since it could
   *    breaks UI
   */
  loadAjaxNavigation : function () {
    if (
      jQuery('body.admin').length == 0
      && jQuery('body > div.container > .section > .content .w-menu-ajax').length > 0
    ) {
      jQuery('body > div.container > .section > .content .w-menu-ajax a')
        .live('click', function (e) {
          jQuery.ajax({
            url: jQuery(this).attr('href'),
            success: function (d, s, x) {
              jQuery('#container')
                .fadeOut(function () {
                  jQuery(this).html(d);
                  sfPlop.loadPlugins();
                })
                .fadeIn()
              ;
              jQuery('body').removeClass('root');
            }
          });
          e.preventDefault();
        })
      ;
    }
  },

  /**
   * ImageZoom
   */
  loadImageZoom : function () {
    var selector = '> .section.RichText img, > .section.Asset img, > .section .content .w-gallery-content-img';
    if (jQuery('body.img-zoom > .container').find(selector).length > 0) {
      jQuery('body.img-zoom > .container').find(selector).each(function () {
        jQuery(this)
          .addClass('w-zoom w-bound')
          .bind('click', function () {
            var
              t = jQuery(this).attr('alt'),
              w = this.naturalWidth,
              h = this.naturalHeight
            ;
            jQuery(this).clone().dialog({
              'title' : t,
              'modal' : true,
              'width' : w,
              'height' : h
            });
          })
        ;
      });
    }
  },

  /**
   * Gallery
   */
  loadGallery : function () {
    if (jQuery.fn.slides) {
      if (jQuery('body > div.container > .CustomGalleryAsset').length > 0) {
        var wGfirst = jQuery('body > div.container > .section > .content .w-gallery:first');
        jQuery('body > div.container > .CustomGalleryAsset > .content').each(function (i, e) {
          jQuery('.w-gallery-contents', wGfirst).append(jQuery(e).html());
          //@todo : menu thumb
          jQuery('.w-menu', wGfirst).append(
            jQuery('<li></li>')
              .html('<a href="#' + (i + 1) + '" class="element">' + (i + 1) + '</a>')
          );
          if (!window.sfPlopAdmin)
            jQuery(e).html('');
          else
            jQuery(e).hide();
        });
      }

      var wG = jQuery('body > div.container > .section > .content .w-gallery')
      if (wG.length) {
				wG.fadeOut()
					.find('img:first').load(
					function () {
						jQuery(this)
							.parents('.w-gallery:first').fadeIn()
							.parents('.section:first').slides(
						{
							container: "w-gallery-contents",
							generatePagination: false,
							paginationClass: "w-gallery-menu",
							prev: 'w-gallery-prev',
							next: 'w-gallery-next',
							crossFade: true,
							effect: 'fade',
	//            hoverPause: true,
							slideSpeed: 500,
							fadeSpeed: 500,
							play: wG.hasClass('autoplay') ? 5000 : false,
							preload: true,
							preloadImage: '/img/loading.gif',
							autoHeight: true
						})
					;

					wG.find('.w-gallery-menu-slider')
						.each(function (i,e) {
							var
								wGSP = jQuery('<div></div>').addClass('w-slider-prev'),
								wGSN = jQuery('<div></div>').addClass('w-slider-next'),
								wGSC = jQuery('<div></div>')
									.addClass('w-slider-container')
									.append(jQuery(e).clone(true)),
								wGSW = jQuery('<div></div>')
									.addClass('w-slider')
									.append(wGSC)
									.append(wGSP)
									.append(wGSN)
									.insertAfter(jQuery(e))
							;

							sfPlop.loadSlider(wGSW);

							jQuery(e).hide().remove();
						})
					;
				});
      }
    }
  },

  /**
   * Clock
   */
  loadClock : function () {
    if (jQuery('body > div.container > .Date .content .clock').length > 0) {
      var
        el = jQuery('body > div.container > .Date .content .clock:first'),
        hour = el.data('hour'),
        minute = el.data('minute'),
        second = el.data('second'),
        clock_server_date, clock_server_text,
        clock_server_hour, clock_server_min, clock_server_sec,
        use_second = false
      ;

      window.clock_write_time = function () {
        clock_server_text = clock_server_hour + ':' + clock_server_min;
        if (use_second)
          clock_server_text += (':' + clock_server_sec);
        jQuery('body > div.container > .Date .content .clock').text(clock_server_text);

      };

      window.clock_server_calc = function () {
        if (clock_server_sec < 10)
          clock_server_sec = '0' + Math.round(clock_server_sec);
        else if (clock_server_sec >= 60) {
          clock_server_sec = 0;
          clock_server_min++;
        }
        if (clock_server_min < 10)
          clock_server_min = '0' + Math.round(clock_server_min);
        else if (clock_server_min >= 60) {
          clock_server_min = 0;
          clock_server_hour++;
        }
        if (clock_server_hour < 10)
          clock_server_hour = '0' + Math.round(clock_server_hour);
        else if (clock_server_hour >= 24) {
          clock_server_hour = 0;
        }
        if (use_second || clock_server_sec == second) {
          clock_write_time();
        }
        clock_server_sec++;
      };

      clock_server_date = new Date(0, 0, 0, hour, minute, second);
      clock_server_hour = clock_server_date.getHours();
      clock_server_min = clock_server_date.getMinutes();
      clock_server_sec = clock_server_date.getSeconds();

      clock_server_calc();
      setInterval('clock_server_calc()', 1000);
    }
  },

  /**
   * Slider
   */
  loadSlider : function (el) {
    if (el == undefined && jQuery('.w-slider').not('.w-on').length > 0) {
      var s = jQuery('.w-slider').not('.w-on');
    }
    else if (el && jQuery(el).not('.w-on').length > 0) {
      var s = el;
    }

    if (s != undefined ) {
      s
        .addClass('w-on')
        .each(function (i, e) {
          var
            wGSW = jQuery(e),
            wGSC = wGSW.find('.w-slider-container'),
            wGSP = wGSW.find('.w-slider-prev'),
            wGSN = wGSW.find('.w-slider-next'),
            wGScount = wGSC.find('.w-menu li').length,
            wGScountVisible = 0,
            wGSwidth =  0,
            wGSEwidth = 0
          ;

          wGSC.find('.w-menu li').each(function (e) {
            wGSwidth += jQuery(this).width();
            if (jQuery(this).width() == 0)
              wGSEwidth = jQuery(this).width();
          });
          wGScountVisible = wGSwidth / wGSEwidth;
          wGSC.find('.w-menu:first').css({
            width: wGSwidth
          });
          wGSC.css({
            height: wGSC.find('li:first').height()
          });

          if (wGSC.find('.w-menu:first').position().left < 0)
            wGSP.removeClass('w-off').addClass('w-on');
          else
            wGSP.removeClass('w-on').addClass('w-off');
          if (( wGSwidth - wGSC.width() ) > ( wGSC.find('.w-menu:first').position().left * -1 ))
            wGSN.removeClass('w-off').addClass('w-on');
          else
            wGSN.removeClass('w-on').addClass('w-off');

          wGSP.bind('click', function (e) {
            if (wGSC.find('.w-menu:first').position().left < 0)
              wGSC.find('.w-menu:first')
                .animate(
                  {left: '+=' + (wGSwidth / wGScount)},
                  function () {
                    if (wGSC.find('.w-menu:first').position().left < 0)
                      wGSP.removeClass('w-off').addClass('w-on');
                    else
                      wGSP.removeClass('w-on').addClass('w-off');
                    if (( wGSwidth - wGSC.width() ) > ( wGSC.find('.w-menu:first').position().left * -1 ))
                      wGSN.removeClass('w-off').addClass('w-on');
                    else
                      wGSN.removeClass('w-on').addClass('w-off');
                  }
                );
          });
          wGSN.bind('click', function (e) {
            if (( wGSwidth - wGSC.width() ) > ( wGSC.find('.w-menu:first').position().left * -1 ))
              wGSC.find('.w-menu:first')
                .animate(
                  {left: '-=' + (wGSwidth / wGScount)},
                  function () {
                    if (wGSC.find('.w-menu:first').position().left < 0)
                      wGSP.removeClass('w-off').addClass('w-on');
                    else
                      wGSP.removeClass('w-on').addClass('w-off');
                    if (( wGSwidth - wGSC.width() ) > ( wGSC.find('.w-menu:first').position().left * -1 ))
                      wGSN.removeClass('w-off').addClass('w-on');
                    else
                      wGSN.removeClass('w-on').addClass('w-off');
                  }
                );
          });
        })
      ;
    }
  },

  loadThemeSwitcher : function () {
    if (jQuery('body > div.container > .ThemeSwitcher').length > 0) {
      jQuery('body > div.container > .ThemeSwitcher input[type=submit]').hide();
      jQuery('body > div.container > .ThemeSwitcher select').bind('change', function() {
        jQuery(this).parents('form:first').trigger('submit');
      });
    }
  }
};

/**
 * Init on DOM ready.
 */
jQuery(document).ready(function () {
  sfPlop.initOnDomReady();
});

/**
 * Init now
 */
sfPlop.init();
