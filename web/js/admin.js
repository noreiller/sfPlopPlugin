var sfPlopAdmin = {
  /**
   * Properties
   */
  _tempVal : new Array(),
  _settings : new Array(),

  /**
   * Init now.
   */
  init : function  () {
    // Settings
    this.val('i18n.confirm', 'Are you sure ?');
    this.val('i18n.information', 'Information');
    this.val('i18n.error', 'Error');
    this.val('i18n.slot_creation_success', 'The block has been created.');
    this.val('i18n.slot_edition_success', 'The block has been updated.');
    this.val('icon_information', 'information.png');
    this.val('icon_error', 'error.png');
    this.val('login_url', '/login');
    this.val('images_url', '/sfPlopPlugin/vendor/famfamfam/silk/');
    this.val('jquery_dialog_id', 'plop_admin_dialog');
    this.val('jquery_dialog_settings', {
      'dialogClass' : 'plop_admin_dialog',
      'width' : 960
    });
    this.val('jquery_dialog_theme_editor_settings', {
      'dialogClass' : 'plop_admin_dialog plop_admin_dialog_theme_editor',
      'width' : 360,
      open: function() { sfPlopAdmin.loadThemeEditor(); }
    });
    this.val('aloha.current_field', null);
    this.val('aloha.current_content', null);
    this.val('aloha.current_field_value', null);
    this.val('aloha.i18n.current', 'en');
    this.val('aloha.repository.name', 'Plop CMS');
    this.val('aloha.repository.url', '/plop/en/ws/repository');
    this.val('aloha.textarea.enabled', 'textarea.w-richtext, #sf_asset_description');
    this.val('aloha.settings', {
      logLevels: {'error': true, 'warn': false, 'info': false, 'debug': false},
      errorhandling : true,
      locale: this.val('aloha.i18n.current'),
      // jQuery: jQuery,
      bundles: { plopcms: '/sfPlopPlugin/js/aloha' },
      floatingmenu: {
        behaviour: 'topalign',
        draggable: false,
        pin: true
      },
      plugins: {
        ribbon: { enable: false },
        table: { config: ['table'] },
        image: {
          config: {
            img: {
              max_width: 'auto',
              max_height: 'auto'
            },
            ui: {
              align: true,
              resize: true, //false,
              meta: true,
              margin: true, //false,
              crop: true //false
            }
          }
        }
      }
    });
  },

  /**
   * Init on DOM ready.
   */
  initOnDomReady : function  () {
    this.loadTempVal();
    this.initRichEditor();
    this.hideSfWDT();
    this.loadPlugins();
    this.loadSlotPlugins();
    this.loadSlotToolbarMenu();
    this.loadAdminThemeSwitcher();
    this.loadThemeEditor();
    sfPlop.hideLoader();
  },

  /**
   * Load slot dedicated plugins. This is called in ajax navigation after the success callback.
   */
  loadSlotPlugins : function () {
    this.loadContentEdition();
    this.slotReorder();
    this.loadAdminAjaxMenuItems();
    this.loadToolbarMenuItems();
  },

  /**
   * Load plugins. This is called in ajax navigation after the success callback.
   */
  loadPlugins : function () {
    this.loadAjaxForm();
    this.loadAutocomplete();
    this.loadDialogCancel();
    this.loadLinkConfirm();
  },

  /**
   * Re bind all slots
   */
  rebindSlots : function () {
    this.loadSlotPlugins();
    sfPlop.loadPlugins();
    this.checkSlotToolbarMenuToggler();
    this.loadSlotToolbarMenu();
    this.loadToolbarMenuItems();
    jQuery(window).trigger('load');
  },

  /**
   * Add a script to the body
   */
  addScript : function (options, callback) {
    if (typeof options != 'object')
      options = { src: options };

    var s = document.createElement('script'); 
    s.type = 'text/javascript'; 
    s.src = options.src;
    for (var a in options) {
      if (a != 'src')
        s.setAttribute(a, options[a])
    }
    if (typeof callback == 'function')
      s.addEventListener('load', callback, false)
    document.body.appendChild(s);
  },

  /**
   * Add a stylesheet to the body
   */
  addCss : function (options) {
    if (typeof options != 'object')
      options = { href: options };

    var s = document.createElement('link'); 
    s.rel = 'stylesheet'; 
    s.type = 'text/css'; 
    s.href = options.href;
    for (var a in options) {
      if (a != 'href')
        s.setAttribute(a, options[a])
    }
    document.head.appendChild(s);
  },

  /**
   * Load scripts listed in an array and trigger window.load when all are loaded.
   */
  loadScripts : function (s) {
    this.val('scripts.loading.count', s.length);
    for (var i=0; i<s.length; i++) {
      jQuery.getScript(s[i], function(e) {
        var nb = sfPlopAdmin.val('scripts.loading.count') - 1;
        if (nb == 0) {
          sfPlopAdmin.removeVal('scripts.loading.count');
          jQuery(window).trigger('load');
        }
        else {
          sfPlopAdmin.val('scripts.loading.count', nb);
        }
      });
    }
  },

  /*
   * Set a variable
   */
  val : function (k, v) {
    if (!v)
      return this._settings[k];
    else
      return this._settings[k] = v;
  },

  /*
   * Remove a variable
   */
  removeVal : function (k) {
    return delete this._settings[k];
  },

  /*
   * Updates the temp values
   */
  loadTempVal: function () {
    for (var k in this._tempVal)
      this.val(k, this._tempVal[k]);
  },

  /*
   * Hide the symfony web debug toolbar which is enabled in dev mode.
   */
  hideSfWDT: function () {
    if (window.sfWebDebugToggleMenu)
      sfWebDebugToggleMenu();
  },

  /*
   * Notify with Gritter library
   * @params s Json settings
   */
  notify: function (s) {
    // Define vars
    var img = this.val('icon_information');
    if (s.type == 'error'){
      img = this.val('icon_error');
    }
    if (s.title == undefined && s.type == 'error') {
      s.title = this.val('i18n.error');
    }
    else if (s.title == undefined) {
      s.title = this.val('i18n.information');
    }
    // Notify
    jQuery.gritter.add(jQuery.extend({
      image : this.val('images_url') + img
    }, s));
  },

  /*
   * Load autocomplete on input.
   */
  loadAutocomplete : function () {
    jQuery('input.w-autocomplete').not('.w-bound').each(function (i, e) {
      var input = jQuery(this);
      jQuery(this)
        .autocomplete({
          source: jQuery(this).attr('data-autocomplete-url'),
          minLength: 3,
          select: function(event, ui) {
            input.val(ui.item.url);
            return false;
          }
        })
        .addClass('w-bound')
        .data('autocomplete')
        ._renderItem = function(ul, item) {
          return jQuery('<li class="w-autocomplete-item"></li>')
            .data('item.autocomplete', item)
            .append(item.html)
            .appendTo(ul)
          ;
        }
      ;
    });
  },

  /*
   * Load dialog cancel.
   */
  loadDialogCancel : function () {
    jQuery('#' + sfPlopAdmin.val('jquery_dialog_id') + ' form')
      .not('.w-ajax')
      .find('.w-form-cancel')
      .not('.w-bound')
      .bind('click', function(e) {
        jQuery('#' + sfPlopAdmin.val('jquery_dialog_id')).dialog('close');
        e.preventDefault();
      })
      .addClass('w-bound')
    ;
  },

  /*
   * Load confirm on links.
   */
  loadLinkConfirm : function () {
    jQuery('a.w-confirm')
      .bind('click', function (e) {
        if (!confirm(sfPlopAdmin.val('i18n.confirm')))
          e.preventDefault();
      })
    ;
  },

  /*
   * Load ajax forms
   */
  loadAjaxForm : function () {
    jQuery('form.w-ajax').not('.w-bound').each(function (i, e) {
      jQuery(this)
        .bind('submit', function(e) {
          var form = jQuery(this);
          jQuery(this).ajaxSubmit({
            beforeSerialize: function() {
              if (jQuery('textarea.w-richtext', form).length > 0)
                jQuery('textarea.w-richtext', form)
                  .val(sfPlopAdmin.getRichContent())
                ;
            },
            success: function(d) {
              if (form.hasClass('w-admin-edit-slot'))
                return sfPlopAdmin.slotEditionSuccess(form, d);
              else if (form.hasClass('w-admin-create-slot'))
                return sfPlopAdmin.slotCreationSuccess(form, d);
              else if (form.hasClass('w-admin-content-slot')) {
                sfPlopAdmin.slotContentEditionSuccess(form, d);
                d = this.val('i18n.slot_edition_success');
              }
              else if (form.hasClass('w-admin-theme-switch'))
                sfPlopAdmin.checkAdminTheme();
              else
                sfPlopAdmin.unloadContentEdition();

              sfPlopAdmin.notify({
                text: d
              });
            },
            error: function (x) {
              if (x.status == 401)
                window.location.href = sfPlopAdmin.val('login_url');
            }
          });
          e.preventDefault();
        })
        .addClass('w-bound')
      ;
      jQuery(this)
        .find('.w-form-cancel')
        .not('.w-bound')
        .bind('click', function(e) {
            sfPlopAdmin.unloadContentEdition();
          if (jQuery(this).parents('.ui-dialog').length > 0)
            jQuery('#' + sfPlopAdmin.val('jquery_dialog_id')).dialog('close');
          e.preventDefault();
        })
        .addClass('w-bound')
      ;
    });
  },

  /**
   * Load the admin menu items on live click event.
   */
  loadAjaxElements : function (container, header) {
    var method = container.data('method') != undefined ? container.data('method') : 'GET';
    jQuery.ajax({
      url: container.attr('href'),
      type: method,
      beforeSend: function () {
        if (container.hasClass('w-confirm')) {
          return confirm(sfPlopAdmin.val('i18n.confirm'));
        }
      },
      success: function (d, s, x) {
        if (container.hasClass('w-ajax-d')) {
          var dialogSettings = 'jquery_dialog_settings';

          if (container.hasClass('w-theme-editor')) {
            dialogSettings = 'jquery_dialog_theme_editor_settings';
          }

          if (jQuery('#' + sfPlopAdmin.val('jquery_dialog_id')).length > 0)
            jQuery('#' + sfPlopAdmin.val('jquery_dialog_id')).remove();

          jQuery('<div></div>')
            .attr('id', sfPlopAdmin.val('jquery_dialog_id'))
            .html(d)
            .appendTo('body')
            .dialog(jQuery.extend({
              title: container.attr('title'),
              close: function (event, ui) {
                jQuery(this)
                  .dialog('destroy')
                  .remove()
                ;
              }
            }, sfPlopAdmin.val(dialogSettings)))
          ;
          sfPlopAdmin.loadAjaxForm();
          jQuery(window).trigger('load');
        }
        else if (container.hasClass('w-admin-refresh')) {
          jQuery('#slot_' + container.attr('rel')).replaceWith(d);
          sfPlopAdmin.notify({
            text: sfPlopAdmin.val('i18n.slot_edition_success')
          });
        }
        else if (container.hasClass('w-admin-credential')) {
          if (x.status == 200 || x.status == 201) {
            var
              value = container.attr('data-value'),
              img = container.find('img:first'),
              src = img.attr('src')
            ;
            if (x.status == 201 || value == 'false') {
              container.attr('data-value', 'true');
              src = src.replace('cross', 'accept', 'g');
            }
            else {
              container.attr('data-value', 'false');
              src = src.replace('accept', 'cross', 'g');
            }
            img.attr('src', src);
          }

          sfPlopAdmin.notify({
            text: d
          });
        }
        else if (container.hasClass('w-ajax-n')) {
          sfPlopAdmin.notify({
            text: d
          });
        }

        sfPlopAdmin.loadPlugins();
        sfPlopAdmin.checkSlotToolbarMenuToggler();
        if (header != undefined) {
          if (container.hasClass('w-publish') && jQuery('.w.w-publish', header).length > 0) {
            jQuery('.w.w-publish[rel=' + container.attr('rel')  + ']', header)
              .toggleClass('w-on w-off')
            ;
          }
          if (container.hasClass('w-edit') && jQuery('.w.w-edit', header).length > 0) {
            jQuery('.w.w-edit[rel=' + container.attr('rel')  + ']', header)
              .toggleClass('w-on w-off')
            ;
          }
          if (container.hasClass('w-admin-delete') && s == 'success') {
            header.parents('.section:first').remove();
            sfPlopAdmin.updateSlotRanks();
          }
          if (container.hasClass('w-admin-down') && s == 'success') {
            var
              t = header.parents('.section:first'),
              u = t.next()
            ;
            t.clone().insertAfter(u);
            t.remove();
            sfPlopAdmin.updateSlotRanks();
          }
          else if (container.hasClass('w-admin-up') && s == 'success') {
            var
              t = header.parents('.section:first'),
              u = t.prev()
            ;
            t.clone().insertBefore(u);
            t.remove();
            sfPlopAdmin.updateSlotRanks();
          }
        }
        sfPlopAdmin.closeSlotToolbarMenus();
      },
      error: function (x) {
        if (x.status == 401)
          window.location.href = sfPlopAdmin.val('login_url');
      }
    });
  },

  /**
   * Update the ranks of the displayed slots.
   */
  updateSlotRanks : function () {
   jQuery(jQuery('#container > .section.Area > .section').length > 0
      ? '#container > .section.Area > .section > .w-toolbar .w.w-rank'
      : '#container > .section > .w-toolbar .w.w-rank'
   ).each(function (i, e) {
      jQuery(e).text(i + 1);
    });
  },

  getRichEditor : function () {
    if (sfPlopAdmin.val('richtext-editor') == 'aloha-editor' && window.sfPlopAloha)
      return sfPlopAloha;
    else
      return null;
  },

  /*
   * Load the rich-text editor.
   */
  initRichEditor : function () {
    this.addScript(this.val('richtext-editor-bridge'), function () {
      sfPlopAdmin.getRichEditor().init();
    });
  },

  /*
   * Load the rich-text edition.
   */
  loadRichEdition : function (f, e) {
    sfPlopAdmin.getRichEditor().load(f, e);
  },

  /*
   * Reset the rich-text edition.
   */
  resetRichEdition : function () {
    sfPlopAdmin.getRichEditor().reset();
  },

  /*
   * Unload the rich-text edition.
   */
  unloadRichEdition : function () {
    sfPlopAdmin.getRichEditor().unload();
  },

  /*
   * Unload the rich-text editor.
   */
  getRichContent : function () {
    return sfPlopAdmin.getRichEditor().getContent();
  },

  /**
   * Load the slot toolbar toggler.
   */
  loadSlotToolbarMenuToggler : function () {
    jQuery('#preview').bind('click', function() {
      sfPlopAdmin.checkSlotToolbarMenuToggler();
    });
  },

  /**
   * Show/hide the slot toolbars.
   */
  checkSlotToolbarMenuToggler : function () {
    if (jQuery('#preview').is(':checked')) {
      jQuery('#preview').parents('.w-button:first')
        .addClass('w-on')
        .removeClass('w-off')
      ;
      jQuery('.section > .w-toolbar').slideUp();
      jQuery('.section > .w-toolbar .w-publish.w-off').each(function() {
        jQuery(this).parents('.section:first').slideUp();
      });
    }
    else {
      jQuery('#preview').parents('.w-button:first')
        .addClass('w-off')
        .removeClass('w-on')
      ;
      jQuery('.section > .w-toolbar').slideDown();
      jQuery('.section').slideDown();
    }

    return jQuery('#preview').is(':checked');
  },

  /**
   * Load the slot toolbar menu on click event.
   */
  loadSlotToolbarMenu : function () {
    jQuery('.section > .w-toolbar').addClass('w-off');
    jQuery('.section > .w-toolbar > .w-menu > .w-menu-dd .close-toolbar')
      .on('click', function (e) {
        jQuery(this).parents('ul:first').slideUp(function (e) {
          jQuery(this).parents('.w-menu-dd:first, .w-toolbar:first')
            .removeClass('w-on')
            .addClass('w-off')
          ;
          jQuery(this).remove();
        });
      })
    ;
    jQuery('.section > .w-toolbar > .w-menu > .w-menu-dd > .element')
      .not('.w-bound')
      .on('click', function (e) {
        if (jQuery('.w-admin-active').length > 0) {
          sfPlopAdmin.unloadContentEdition();
        }
        if (jQuery(this).next().is('ul')) {
          jQuery(this).next().slideUp(function (e) {
            jQuery(this).parents('.w-menu-dd:first, .w-toolbar:first')
              .removeClass('w-on')
              .addClass('w-off')
            ;
            jQuery(this).remove();
          });
        }
        else {
          var el = jQuery(this);
          sfPlopAdmin.closeSlotToolbarMenus();
          jQuery.ajax({
            url: jQuery(this).attr('href'),
            success: function (d, s, x) {
              jQuery(d)
                .css({'display' : 'none'})
                .insertAfter(el)
                .slideDown()
                .css({zIndex: 105})
              ;
              el.parents('.w-menu-dd:first, .w-toolbar:first')
                .removeClass('w-off')
                .addClass('w-on')
              ;
              sfPlopAdmin.loadSlotPlugins();
            },
            error: function (x) {
              if (x.status == 401)
                window.location.href = sfPlopAdmin.val('login_url');
            }
          });
        }
        e.preventDefault();
      })
      .addClass('w-bound')
    ;
    if (!sfPlopAdmin.checkSlotToolbarMenuToggler())
      jQuery('.section > .w-toolbar')
        .slideDown('slow')
      ;
    this.loadSlotToolbarMenuToggler();
    this.loadToolbarMenuItems();
  },

  /**
   * Close all the opened slot toolbar menus.
   */
  closeSlotToolbarMenus : function () {
    jQuery('.section > .w-toolbar > .w-menu > .w-menu-dd > ul')
      .slideUp(function (e) {
        jQuery(this).parents('.w-menu-dd:first, .w-toolbar:first')
          .removeClass('w-on')
          .addClass('w-off')
        ;
        jQuery(this).remove();
      })
    ;
  },

  /**
   * Load the toolbar menu items with ajax events on live click event.
   */
  loadToolbarMenuItems : function () {
    jQuery('.section > .w-toolbar > .w-menu > .w-menu-dd > ul a.w-ajax, body > .w-toolbar > .nav > .w-menu a.w-ajax')
      .not('.w-bound')
      .on('click', function (e) {
        sfPlopAdmin.loadAjaxElements(
          jQuery(this),
          jQuery(this).parents('.w-toolbar:first')
        );
        e.preventDefault();
      })
      .addClass('w-bound')
    ;
  },

  /**
   * Load the items from the admin container with ajax events on live click event.
   */
  loadAdminAjaxMenuItems : function () {
    jQuery('body.admin-bo > .container a.w-ajax')
    .not('.w-bound')
      .on('click', function (e) {
        sfPlopAdmin.loadAjaxElements(jQuery(this));
        e.preventDefault();
      })
      .addClass('w-bound')
    ;
  },

  /*
   * Load the content edition.
   */
  loadContentEdition : function () {
    sfPlopAdmin.launchContentEdition('body', true);
    jQuery('.section > .w-toolbar a.w-admin-content').on('click', function (e) {
      var
        container = jQuery(this).parents('.section:first').addClass('w-admin-active'),
        linkToContainer = jQuery(this)
      ;
      if (!jQuery(this).hasClass('w-admin-rich'))
        jQuery('> .content:first', container)
          .hide()
        ;
      jQuery('> .admin', container).remove();
      jQuery.ajax({
        url: jQuery(this).attr('href'),
        success: function (d, s, x) {
          jQuery('<div></div>')
            .html(jQuery(d).find('.content').html())
            .addClass('admin w-expose')
            .appendTo(container)
          ;

          sfPlopAdmin.loadPlugins();

          if (linkToContainer.hasClass('w-admin-rich'))
            sfPlopAdmin.launchContentEdition(container);
          else
            sfPlopAdmin.launchContentEdition(container, true);

          if (jQuery(d)[1] != undefined) {
            var s = new Array();
            for (var i=1; i<jQuery(d).length; i++) {
              s.push(jQuery(jQuery(d)[i]).attr('src'));
            }
            sfPlopAdmin.loadScripts(s);
          }
        },
        error: function (x) {
          if (x.status == 401)
            window.location.href = sfPlopAdmin.val('login_url');
        }
      });
      sfPlopAdmin.closeSlotToolbarMenus();
      e.preventDefault();
    });
  },

  /*
   * Launch the content edition of the given element.
   */
  launchContentEdition : function (e, textarea) {
    if (textarea == undefined)
      textarea = false;
    else
      textarea = true;

    if (jQuery('textarea.w-richtext', e).length > 0) {
      if (textarea == false)
        jQuery('> .content:first', e)
          .attr('id', jQuery('textarea.w-richtext', e).attr('id') + '-aloha')
        ;
      this.loadRichEdition(
        jQuery('textarea.w-richtext', e).attr('id'),
        textarea
      );
    }
  },

  /*
   * Unload the content edition.
   */
  unloadContentEdition : function () {
    jQuery('.w-admin-active').each(function(i, e) {
      sfPlopAdmin.unloadRichEdition();
      jQuery('> .content:first', e).attr('id', '');
      jQuery(e).find('.admin').remove();
      sfPlop.unexpose(jQuery(e).find('.content'));
      jQuery(e).find('.content').show();
      jQuery(e).removeClass('w-admin-active');
    });
  },

  /**
   * When the slot has been created, update the page with the data from the form
   * @param  {Object} form jQuery object
   * @param  {Object} data Response from the form
   */
  slotCreationSuccess: function (form, data) {
    if (jQuery('#container > .section.Area').length > 0)
      jQuery(data).appendTo('#container > .section.Area');
    else if (jQuery('#container > .section').length > 0)
      jQuery(data).insertAfter('#container > .section:last');
    else
      jQuery(data).prependTo('#container');
    sfPlopAdmin.notify({
      text: sfPlopAdmin.val('i18n.slot_creation_success')
    });
    jQuery('#' + sfPlopAdmin.val('jquery_dialog_id')).dialog('close');
    sfPlopAdmin.rebindSlots();
  },

  /**
   * When the slot content has been edited, update the page with the data from 
   * the form
   * @param  {Object} form jQuery object
   * @param  {Object} data Response from the form
   */
  slotContentEditionSuccess : function (form, data) {
    this.unloadContentEdition();
    if (jQuery('#slot_' + form.attr('rel')).length > 0)
      jQuery('#slot_' + form.attr('rel')).replaceWith(d);
    sfPlopAdmin.rebindSlots();
  },

  /**
   * When the slot options has been edited, update the page with the data from 
   * the form
   * @param  {Object} form jQuery object
   * @param  {Object} data Response from the form
   */
  slotEditionSuccess: function (form, data) {
    var slot = form.attr('rel');
    if (jQuery('#slot_' + slot).length > 0)
      jQuery('#slot_' + slot).replaceWith(data);
    sfPlopAdmin.notify({
      text: sfPlopAdmin.val('i18n.slot_edition_success')
    });
    jQuery('#' + sfPlopAdmin.val('jquery_dialog_id')).dialog('close');
    sfPlopAdmin.rebindSlots();
  },

  /**
   * Load the jQuery UI sortable behavior on slots or reindexing them
   */
  slotReorder: function () {
    if (this.val('slot-sortable')) {
      jQuery(this.val('slot-sortable')).sortable('refresh');
    }
    else {
      var sortable = jQuery('#container > .section.Area > .section[data-handle-url]').length > 0
        ? '#container > .section.Area'
        : '#container'
      ;
      jQuery(sortable).sortable({
        forcePlaceholderSize : true,
        handle : '.w-handle',
        helper : 'clone',
        items: '> .section[data-handle-url]',
        placeholder: 'w-placeholder',
        start : function(event, ui) {
          ui.placeholder
            .addClass(ui.item.attr('class'))
            .html(jQuery('<div class="content">&nbsp;</div>'))
          ;
        },
        update : function(event, ui) {
          var
            l = jQuery(sfPlopAdmin.val('slot-sortable')).sortable('toArray'),
            order = ''
          ;
          for (var i in l) {
            var j = Number(i);
            if (!isNaN(j))
              order += ('&order[' + (l[j]).toString().replace('slot_', '') + ']=' + (j +1));
          }

          jQuery.ajax({
            url: jQuery('#container .section[data-handle-url]:first').attr('data-handle-url'),
            data: order,
            type: 'POST',
            success: function (d, s, x) {
              sfPlopAdmin.notify({
                text: d
              });
              sfPlopAdmin.updateSlotRanks();
            }
          });

        }
      });
      this.val('slot-sortable', sortable);
    }
  },

  /**
   * Load the UI for the admin theme switcher
   */
  loadAdminThemeSwitcher : function () {
    var list = jQuery('<ul />').attr('id', 'admin-theme-switch-widget');

    jQuery('#admin-theme-switch').find('option').each(function () {
      jQuery('<a />')
        .attr('href', '#admin-theme-switch')
        .text(jQuery(this).text())
        .addClass('w-img-link')
        .css('background-color', jQuery(this).data('color'))
        .attr('data-value', jQuery(this).val())
        .attr('data-text', jQuery(this).text())
        .appendTo(list)
        .wrap('<li />')
      ;
    });

    jQuery('#admin-theme-switch').parents('form:first')
      .append(list)
      .find('input[type=submit], select').hide()
    ;

    jQuery('#admin-theme-switch-widget a').bind('click', function(e) {
      jQuery('#admin-theme-switch')
        .val(jQuery(this).data('value'))
        .trigger('change')
      ;
      e.preventDefault();
    });

    jQuery('#admin-theme-switch').bind('change', function() {
      jQuery(this).parents('form:first').trigger('submit');
    });
  },

  /**
   * Check the user admin theme
   */
  checkAdminTheme : function (theme) {
    if (!theme)
      theme = jQuery('#admin-theme-switch').val();

    var
      bodyClass = jQuery('body').attr('class'),
      bodyThemes = bodyClass.split(' '),
      re = new RegExp("admin-theme-(.*)")
    ;
    for (var k in bodyThemes) {
      if (re.test(bodyThemes[k]))
        bodyThemes.splice(bodyThemes.indexOf(bodyThemes[k]), 1);
    }
    bodyThemes.push(theme);

    bodyClass = bodyThemes.join(' ');
    jQuery('body').attr('class', bodyClass);
  },

  /**
   * Load the UI for the theme editor
   */
  loadThemeEditor : function () {
    this.initThemeEditor();
    // this.initLayoutEditor();
  },

  initThemeEditor : function(element) {
    jQuery('.w-theme-editor-transparent').bind('click', function () {
      jQuery('#' + jQuery(this).data('target')).val('transparent').trigger('change');
    });
    jQuery('.w-theme-editor-delete').bind('click', function () {
      jQuery('#' + jQuery(this).data('target')).val('').trigger('change');
    });
    jQuery('.w-theme-editor-color')
      .ColorPicker({
        onSubmit: function(hsb, hex, rgb, el) {
          jQuery(el).val('#' + hex).trigger('change');
          jQuery(el).ColorPickerHide();
        },
        onBeforeShow: function () {
          jQuery(this).ColorPickerSetColor(this.value);
        }
      })
      .bind('keyup', function(){
        jQuery(this).ColorPickerSetColor(this.value);
      })
    ;

    jQuery('form.w-theme-editor fieldset > legend')
      .addClass('w-clickable ui-state-default ui-corner-all')
      .each(function (i) {
        if (i > 0)
          jQuery(this).parents('fieldset:first')
            .addClass('ui-widget-content')
            .removeClass('w-on').addClass('w-off')
            .find('> .form-row').slideUp()
          ;
        else
          jQuery(this).parents('fieldset:first')
            .addClass('ui-widget-content')
            .removeClass('w-off').addClass('w-on')
          ;
      })
      .bind('click', function () {
        if (jQuery(this).parents('fieldset:first').hasClass('w-on')
          && jQuery('form.w-theme-editor fieldset.w-on').length > 1)
          jQuery(this).parents('fieldset:first')
            .removeClass('w-on').addClass('w-off')
            .find('> .form-row').slideUp()
          ;
        else if (jQuery(this).parents('fieldset:first').hasClass('w-off')) {
          jQuery('form.w-theme-editor fieldset.w-on')
            .removeClass('w-on').addClass('w-off')
            .find('> .form-row').slideUp()
          ;
          jQuery(this).parents('fieldset:first')
            .removeClass('w-off').addClass('w-on')
            .find('> .form-row').slideDown()
          ;
        }
      })
    ;

    // add a class to detect them instead of 2 tags
    jQuery('input, select', jQuery('form.w-theme-editor')).change(function(){
      sfPlopAdmin.checkThemeElements();
    });

    jQuery('.w-form-cancel', jQuery('form.w-theme-editor')).bind('click', function(){
      jQuery(this).parents('form:first').find('.w-form-reset').trigger('click');
    });

    jQuery('.w-form-reset', jQuery('form.w-theme-editor')).bind('click', function(){
      jQuery(this).parents('form:first')[0].reset();
      sfPlopAdmin.checkThemeElements();
    });
  },

  updateTheme : function(element) {
    var
      e = element.data('target'),
      r = element.data('property'),
      v = element.val()
    ;

    // Exceptions for : background-image, background-position, border-style
    if (r == 'background-image' && v != '') {
      v = 'url(' + v + ')';
    }
    else if ((r == 'background-position-x' || r == 'background-position-y') && v != '') {
      var pv = jQuery(e).css('background-position');
      if (pv != undefined) {
        var p = pv.split(' ');
        if (p[0] != ' ' && r == 'background-position-x') {
          v = v + ' ' + p[1];
        }
        else if (p[0] != ' ' && r == 'background-position-y') {
          if (jQuery('body > .w-toolbar').length > 0 && v == 'top')
            v = p[0] + ' ' + jQuery('body > .w-toolbar').height();
          else
            v = p[0] + ' ' + v;
        }
        r = 'background-position';
      }
    }
    else if (r == 'border-style') {
      // We use "border-top-width" property since "border-with" returns "".
      var pv = jQuery(e).css('border-top-width');
      if (pv == undefined || pv == '') {
        v = '';
      }
    }

    if (e != undefined && r != undefined && v != undefined)
      jQuery(e).css(r, v);
  },
  checkThemeElements : function() {
    jQuery('input, select', jQuery('form.w-theme-editor')).each(function(){
      sfPlopAdmin.updateTheme(jQuery(this));
    });
  },

  initLayoutEditor : function(options) {
    jQuery('#layout-edition-slider').slider({
      max: 99,
      min: 1,
      range: true,
      values: [options.left, options.right],
      slide: function(event, ui) {
        sfPlopAdmin.updateSlotsWidth(ui.values[0], ui.values[1]);
        sfPlopAdmin.updateSliderValue(ui.values);
      }
    });

    sfPlopAdmin.updateSliderValue(jQuery('#layout-edition-slider').slider('values'));

    jQuery('.cancel', jQuery('#style-elements')).click(function(){
      jQuery(this).parents('form:first').find('.reset').trigger('click');
    });

    jQuery('.reset', jQuery('#style-elements')).click(function(){
      jQuery(this).parents('form:first')[0].reset();
      jQuery('#layout-edition-slider').slider('values', 0, options.left);
      jQuery('#layout-edition-slider').slider('values', 1, options.right);
      sfPlopAdmin.updateSliderValue([options.left, options.right]);
      sfPlopAdmin.updateSlotsWidth(options.left, options.right);
    });
  },
  updateSliderValue: function(values) {
    jQuery('#layout-edition-slider')
      .slider()
      .find('.ui-slider-handle').each(function(e){
        jQuery(this)
          .html(values[e] + '%')
          .css({
            'text-decoration':'none',
            'line-height':'1.2em',
            'padding':'0 0.5em',
            'width':'auto'
          });
      });
  },
  updateSlotsWidth: function(left, right) {
    var
      l = left + '%',
      r = (100 - right) + '%',
      c = (100 - left - (100 - right)) + '%',
      lc = (left + (100 - left - (100 - right))) + '%',
      cr = ((100 - left - (100 - right)) + (100 - right)) + '%';

    jQuery('#layout_edit_block_left').val(l);
    jQuery('#layout_edit_block_center').val(c);
    jQuery('#layout_edit_block_right').val(r);
    jQuery('#layout_edit_block_left_center').val(lc);
    jQuery('#layout_edit_block_center_right').val(cr);

    sfPlopAdmin.checkThemeElements();
  }
};

/**
 * Init on DOM ready.
 */
jQuery(document).ready(function (){
  sfPlopAdmin.initOnDomReady();
});

/**
 * Init now
 */
sfPlopAdmin.init();

