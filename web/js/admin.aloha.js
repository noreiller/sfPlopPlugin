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
  }
};