SubscribeLink = function ($element, options) {
  if ($element.data('subscribeLink') != null) {
    $element.data('subscribeLink').destroy();
  }

  this.$element = $element;
  this.options = $.extend({}, {
    selector: null
  }, options);

  this.registerEvents();

  $element.data('subscribeLink', this);
};

SubscribeLink.prototype.destroy = function () {
  this.$element.off('click.subscribeLink');
};

SubscribeLink.prototype.registerEvents = function () {
  var $this = this;

  if (this.options.selector === null) {
    this.$element.on('click.subscribeLink', function(e) {
      $this.handleClickEvent(e, $(this));
    });
  } else {
    this.$element.on('click.subscribeLink', this.options.selector, function(e) {
      $this.handleClickEvent(e, $(this));
    });
  }
};

SubscribeLink.prototype.handleClickEvent = function (e, $element) {
  e.preventDefault();
  e.stopImmediatePropagation();

  if (typeof $element.attr('disabled') !== typeof undefined && $element.attr('disabled') !== false)
    return;

  var href = $element.attr('href');

  $element.attr('disabled', 'disabled');
  $.post( href )
    .done(function(data) {
      if (data.status === 'success') {
        var textNode = $('.journal-thumbnails .subscribe-link');

        if (data.currentUserSubscribedIt) {
          $element.addClass('subscribed-it');
          $element.attr('data-original-title', 'Отписаться');

          textNode.map(function (subscribe, index) {
            if ($element[0].href === index.href) {
              index.setAttribute('data-original-title', 'Отписаться');
              index.setAttribute('title', '');
              index.classList.add('subscribed-it');
            }
          });
        }
        else {
          $element.removeClass('subscribed-it');
          $element.attr('data-original-title', 'Подписаться');
          textNode.map(function (subscribe, index) {
            if ($element[0].href === index.href) {
              index.setAttribute('data-original-title', 'Подписаться');
              index.setAttribute('title', '');
              index.classList.remove('subscribed-it');
            }
          });
        }
      }
    })
    .always(function() {
      $element.removeAttr('disabled');
      $element.blur();
    });
};

if ($.fn.subscribeLink == null) {
  $.fn.subscribeLink = function (options) {
    options = options || {};

    if (typeof options === 'object') {
      this.each(function () {
        var instanceOptions = $.extend(true, {}, options);

        var instance = new SubscribeLink($(this), instanceOptions);
      });

      return this;
    } else {
      throw new Error('Invalid arguments for SubscribeLink: ' + options);
    }
  };
}