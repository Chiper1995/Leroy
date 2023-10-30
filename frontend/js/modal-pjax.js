ModalPjax = function(options) {

  this.options = $.extend({}, {
    url: '',
    pjaxId: '',
    title: '',
    className: '',
    buttons: {}
  }, options);

  this.normalizeButtons();
};

ModalPjax.prototype.normalizeButtons = function() {
  var buttons;

  buttons = this.options.buttons;

  if (typeof buttons !== "object") {
    throw new Error("Please supply an object of buttons");
  }

  if (buttons['cancel'] === undefined)
    buttons.cancel = {
      label: 'Отмена',
      className: 'btn-default'
    };

  $.each(buttons, function(key, button) {
    if ($.type(button) !== "object") {
      throw new Error("button with key " + key + " must be an object");
    }

    if (!button.label) {
      button.label = key;
    }

    if (!button.className) {
      button.className = 'btn-default';
    }

    if (button.callback) {
      if (!$.isFunction(button.callback))
        throw new Error("button.callback must be a function");
    }
  });
};

ModalPjax.prototype.open = function() {
  var $modal = this.render();

  this.$modal = $modal;
  $('body').append($modal);

  var pjaxOptions = {
    container: '#'+this.options.pjaxId,
    push: false,
    replace: false,
    history: false,
    timeout: 10000,
    scrollTo: false
  };

  $.pjax(
    $.extend({}, pjaxOptions, {url: this.options.url})
  );

  this.registerEvents($modal);
  this.registerPjaxEvents($modal, pjaxOptions);

  if ($.isFunction(this.options.beforeShow))
    this.options.beforeShow.call(this);

  $modal.modal();
};

ModalPjax.prototype.hide = function() {
  this.$modal.modal('hide');
};

ModalPjax.prototype.setUrl = function(url) {
  this.options.url = url;
};

ModalPjax.prototype.render = function() {
  var $modal = $(
    '<div class="modal modal-pjax ' + this.options.className + ' fade" role="dialog">' +
      '<div class="modal-dialog">' +
        '<div class="modal-content">' +
          '<div class="modal-header"></div>' +
          '<div class="modal-body"></div>' +
          '<div class="modal-footer"></div>' +
        '</div>' +
      '</div>' +
    '</div>'
  );

  var $header = this.headerRender();
  $modal.find('.modal-header').append($header);

  var $body = this.bodyRender();
  $modal.find('.modal-body').append($body);

  var $footer = this.footerRender();
  $modal.find('.modal-footer').append($footer);

  return $modal;
};

ModalPjax.prototype.headerRender = function() {
  return $(
    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
    '<h4 class="modal-title">' + this.options.title + '</h4>'
  );
};

ModalPjax.prototype.bodyRender = function() {
  return $(
    '<div id="' + this.options.pjaxId + '">' +
    '</div>'
  );
};

ModalPjax.prototype.footerRender = function() {
  var buttons;
  var buttonsStr;

  buttons = this.options.buttons;

  buttonsStr = '';
  $.each(buttons, function(key, button) {
    buttonsStr += "<button data-mp-handler='" + key + "' type='button' class='btn " + button.className + "'>" + button.label + "</button>"
  });

  return $(buttonsStr);
};

ModalPjax.prototype.registerEvents = function($modal) {
  var $this = this;

  $modal.on('hidden.bs.modal', function(e) {
    if (e.target === this) {
      $modal.remove();
    }
  });

  $modal.on('click', '.modal-footer button', function (e) {
    var key = $(this).data('mp-handler');
    var buttons = $this.options.buttons;

    if (buttons[key] !== undefined){
      var button = buttons[key];

      if (button.callback) {
        if (button.callback.call($this) !== false)
          $this.hide();
      } else {
        $this.hide();
      }
    }
  });
};

ModalPjax.prototype.registerPjaxEvents = function($modal, pjaxOptions) {
  $modal.pjax('#'+this.options.pjaxId+' a', pjaxOptions);

  $modal.on('submit', '#'+this.options.pjaxId+' form[data-pjax]', function (event) {
    $.pjax.submit(event, pjaxOptions);
  });
};