var sfPlopAloha = {
  /*
   * Status of the rich-text editor. Can be false or true.
   */
  _status : false,

  /*
   * The id of the editor
   */
  _editor : '',

  /*
   * The id of the editor field
   */
  _field : '',

  /*
   * The value of the field before beeing edited
   */
  _value : '',

  /**
   * Init the rich text editor
   */
  init : function () {
    if (this._status == false) {
      ( function ( window, undefined ) {
        var Aloha = window.Aloha || ( window.Aloha = {} );
        Aloha.settings = sfPlopAdmin.val('aloha.settings');
        sfPlopAloha._status = true;
        sfPlopAloha.loadPlugins();
      })(window);

      sfPlopAdmin.addCss(sfPlopAdmin.val('richtext-editor-css'));
      sfPlopAdmin.addScript(sfPlopAdmin.val('richtext-editor-script'));
    }
  },

  /*
   * Bind the rich-text editor and load the edition of the given node
   */
  bind : function (f, t) {
    this.init();
    Aloha.ready(function() {
      sfPlopAloha.load(f, t);
    });
  },

  /*
   * Load the rich-text editor.
   * String f : id of the field
   * Boolean t : field is a textarea
   */
  load : function (f, t) {
    if (f != undefined && this._status != true) {
      this.bind(f, t);
    }
    else if (f != undefined) {
      if (this._field != '' && this._editor != '' )
        this.unload();

      this._field = f;
      this._editor = f + '-aloha';
      this._value = jQuery('#' + this._field).val();

      Aloha.jQuery('#' + ((t == true) ? this._field : this._editor))
        .show()
        .aloha()
      ;
    }
    else if (sfPlopAdmin.val('aloha.textarea.enabled') != undefined) {
      Aloha.jQuery(sfPlopAdmin.val('aloha.textarea.enabled')).aloha();
    }
  },

  /*
   * Reset the rich-text edition.
   */
  reset : function () {
    jQuery('#' + this._field).val(this._value);
    jQuery('#' + this._editor).html(this._value);
  },

  /*
   * Unload the rich-text editor.
   */
  unload : function () {
    if (this._field != '') {
      this.reset();
      Aloha.jQuery('#' + this._editor).mahalo();
      this._field = '';
      this._editor = '';
      this._value = '';
    }
  },

  /*
   * Clean the content and send it.
   */
  getContent : function () {
    this.clean();

    return window.Aloha.getEditableById(this._editor).getContents()
  },

  /*
   * Clean a rich-text node :
   * * remove css property "background-color" on A tags
   * * remove css property "background-color, display, height, width"
   * on IMG tags
   * * remove DIV tags
   */
  clean : function () {
    var $editor = jQuery('#' + this._editor);

    $editor
      .find('a')
      .css({'background-color' : ''})
    ;

    $editor
      .find('img')
      .css({
        'background-color' : '',
        'display' : ''
      })
    ;

    for (var i = 0; i <= jQuery('div', $editor).length; i++) {
      if (!jQuery('div:first', $editor).is(':empty'))
        jQuery('div:first :first-child', $editor).unwrap();
      else
        jQuery('div:first', $editor).remove();
    }
  },

  /*
   * Load the rich-text editor plugins
   */
  loadPlugins : function () {
  },

  /*
   * Load the repository plugin.
   */
  loadRepository : function () {

//    var GENTICS = window.GENTICS;
//    /**
//     * Create the Repositories object. Namespace for Repositories
//     * @hide
//     */
//    if ( !GENTICS.Aloha.Repositories ) GENTICS.Aloha.Repositories = {};
//
//    /**
//     * register the plugin with unique name
//     */
//    GENTICS.Aloha.Repositories.plop = new GENTICS.Aloha.Repositories('org.plop.aloha.Repositories');
//
//    /**
//    * Internal folder structur.
//    * @hide
//    */
//    GENTICS.Aloha.Repositories.plop.folder =[];
//
//    /**
//     * Init method of the repository. Called from Aloha Core to initialize this repository
//     * @return void
//     * @hide
//     */
//    GENTICS.Aloha.Repositories.plop.init = function() {
//      var
//        that = this,
//        url_href = "/"
//      ;
//      this.repositoryUrl = sfPlopAdmin.val('aloha.repository.url');
//      this.repositoryName = 'plop';
//      this.repositoryId = 'org.plop.aloha.Repositories';
//    };
//
//    /**
//    * Searches a repository for object items matching query if objectTypeFilter.
//    * If none found it returns null.
//    */
//    GENTICS.Aloha.Repositories.plop.query = function(p, callback) {
//      var req_str = p.queryString;
//      if (req_str != null && p.inFolderId == null) {
//        callback.call(this, this.findInDomain(req_str));
//      }
//      if (p.inFolderId != null) {
//        folderId = p.inFolderId;
//        if (folderId.match(new RegExp(this.repositoryId))) {
//          folderId = folderId.split(this.repositoryId)[0];
//        }
//        callback.call(this, this.getObjectsAtUrl(folderId));
//      }
//    };
//
//    /**
//      * returns the folder structure as parsed at init.
//      */
//    GENTICS.Aloha.Repositories.plop.getChildren = function( p, callback) {
//      var req_url = p.inFolderId.split(this.repositoryId)[0];
//      callback.call(this, this.getObjectsAtUrl(req_url));
//    };
//
//    GENTICS.Aloha.Repositories.plop.getObjectsAtUrl = function(url_href) {
//      var
//        result = []
//        that = this
//      ;
//
//      // Folders
//      jQuery.ajax({
//        url: that.repositoryUrl,
//        data: 'type=folder&folder=' + url_href,
//        dataType: "json",
//        context: document.body,
//        async: false, // plugin init should wait for success b4 continuing
//        success: function(data) {
//          jQuery.each(data,function(k, v){
//            result.push(new that.Folder({
//              id: url_href + '/' + k,
//              name: k,
//              objectType: 'folder',
//              parentId: url_href,
//              repositoryId: that.repositoryId,
//              type: 'folder',
//              url: url_href + '/' + k
//            }));
//          });
//        }
//      });
//
//      // Files
//      if (url_href != "/" && url_href != "" ) {
//        jQuery.ajax({
//          url: that.repositoryUrl,
//          data: 'type=file&folder=' + url_href,
//          dataType: "json",
//          context: document.body,
//          async: false, // plugin init should wait for success b4 continuing
//          success: function(data) {
//            jQuery.each(data, function(i, v) {
//              result.push(new GENTICS.Aloha.Repositories.Document ({
//                id: v.url,
//                name: v.name,
//                objectType: 'document',
//                parentId: url_href,
//                repositoryId: that.repositoryId,
//                type: 'document',
//                url: v.url
//              }));
//            });
//          }
//        });
//      }
//
//      return result
//    };
//
//    GENTICS.Aloha.Repositories.plop.findInDomain = function(queryString) {
//      var
//        result = [],
//        that = this
//      ;
//
//      // Assets
//      jQuery.ajax({
//        url: that.repositoryUrl,
//        dataType: "json",
//        contentType:"application/json",
//        context: document.body,
//        data: 'type=file&folder=/Assets&term=' + queryString,
//        async: false, // plugin init should wait for success b4 continuing
//        success: function(data) {
//          jQuery.each(data, function(i, v) {
//            var re = new RegExp(queryString);
//            if (!v.name) {v.name = "No name";}
//            if (v.url.match(re) || v.name.match(re)) {
//              result.push(new GENTICS.Aloha.Repositories.Document ({
//                id: v.url,
//                name: v.name,
//                objectType: v.type,
//                repositoryId: that.repositoryId,
//                type: 'document',
//                url: v.url
//              }));
//            }
//          });
//        }
//      });
//
//      // Links
//      jQuery.ajax({
//        url: that.repositoryUrl,
//        dataType: "json",
//        contentType:"application/json",
//        context: document.body,
//        data: 'type=file&folder=/Links&term=' + queryString,
//        async: false, // plugin init should wait for success b4 continuing
//        success: function(data) {
//          jQuery.each(data, function(i, v) {
//            var re = new RegExp(queryString);
//            if (!v.name) {v.name = "No name";}
//            if (v.url.match(re) || v.name.match(re)) {
//              result.push(new GENTICS.Aloha.Repositories.Document ({
//                id: v.url,
//                name: v.name,
//                objectType: 'link',
//                repositoryId: that.repositoryId,
//                type: 'document',
//                url: v.url
//              }));
//            }
//          });
//        }
//      });
//
//      return result
//    };
//
//    GENTICS.Aloha.Repositories.plop.Folder = function (data) {
//      //window.Utils.applyProperties(this,data);
//      GENTICS.Aloha.Repositories.plop.Folder.superclass.constructor.call(this,data);
//    };
//
//    Ext.extend(GENTICS.Aloha.Repositories.plop.Folder, GENTICS.Aloha.Repositories.Folder, {
//    });
//
//    GENTICS.Aloha.Repositories.plop.Document = function (data) {
//      //window.Utils.applyProperties(this,data);
//      GENTICS.Aloha.Repositories.plop.Document.superclass.constructor.call(this,data);
//    };
//
//    Ext.extend(GENTICS.Aloha.Repositories.plop.Document, GENTICS.Aloha.Repositories.Document, {
//    });
//
//
//    /**
//     * Make the given jQuery object (representing an object marked as object of this type)
//     * clean. All attributes needed for handling should be removed.
//     * @param {jQuery} obj jQuery object to make clean
//     * @return void
//     */
//    GENTICS.Aloha.Repositories.plop.makeClean = function (obj) {
//      obj.removeAttr('data-window-aloha-repository');
//    };
  }
};