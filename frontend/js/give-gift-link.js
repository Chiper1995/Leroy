GiveGiftLink = function ($element, options) {
  if ($element.data('giveGiftLink') != null) {
    $element.data('giveGiftLink').destroy();
  }

  this.$element = $element;
  this.options = $.extend({}, {
    selector: null,
    modalPjaxId: '',
    modalTitle: ''
  }, options);

  this.journalId = null;
  this.modal = this.createModal();
  this.registerEvents();

  $element.data('giveGiftLink', this);
};

GiveGiftLink.prototype.destroy = function () {
  this.$element.off('click.giveGiftLink');
};

GiveGiftLink.prototype.registerEvents = function () {
  var $this = this;

  if (this.options.selector === null) {
    this.$element.on('click.giveGiftLink', function(e) {
      $this.handleClickEvent(e, $(this));
    });
  } else {
    this.$element.on('click.giveGiftLink', this.options.selector, function(e) {
      $this.handleClickEvent(e, $(this));
    });
  }
};

GiveGiftLink.prototype.createModal = function () {
  var $this = this;

  return new ModalPjax({
    url: null,
    pjaxId: this.options.modalPjaxId,
    title: this.options.modalTitle,
    buttons: {
      ok: {
        label: 'Подарить',
        className: 'btn-primary save-btn',
        callback: function() {
          $this.modalOkHandler();
          return false;
        }
      },
      cancel: {
        label: 'Отмена',
        className: 'btn-default cancel-btn'
      }
    },
    beforeShow: function() {
      $this.registerModalPjaxEvents();
    }
  });
};

GiveGiftLink.prototype.modalOkHandler = function() {
  var $modal = this.modal.$modal;
  $modal.find('#give-gift-form').submit();
};

GiveGiftLink.prototype.registerModalPjaxEvents = function() {
  var $this = this;
  var $modal = this.modal.$modal;

  $modal.find('#'+this.options.modalPjaxId).on('pjax:complete', function() {
    $modal.find('#give-gift-form-journal-id').val($this.journalId);
    if ($modal.find('#give-gift-form-saved').val() === '1'){
      var familyPoints = $modal.find('#give-gift-form-family-points').val();
      var journalPoints = $modal.find('#give-gift-form-journal-points').val();
      $('.card-info .points .caption').text(familyPoints + ' баллов');
      $('.view-journal-header .points .value').text(journalPoints);

      $this.modal.hide();
    }
  });
};

GiveGiftLink.prototype.handleClickEvent = function (e, $element) {
  e.preventDefault();
  e.stopImmediatePropagation();
  var href = $element.attr('href');
  this.modal.setUrl(href);
  this.journalId = $element.attr('data-journal-id');
  this.modal.open();
};

if ($.fn.giveGiftLink == null) {
  $.fn.giveGiftLink = function (options) {
    options = options || {};

    if (typeof options === 'object') {
      this.each(function () {
        var instanceOptions = $.extend(true, {}, options);

        var instance = new GiveGiftLink($(this), instanceOptions);
      });

      return this;
    } else {
      throw new Error('Invalid arguments for GiveGiftLink: ' + options);
    }
  };
}