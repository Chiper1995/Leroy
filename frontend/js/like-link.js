LikeLink = function ($element, options) {
  if ($element.data('likeLink') != null) {
    $element.data('likeLink').destroy();
  }

  this.$element = $element;
  this.options = $.extend({}, {
    selector: null
  }, options);

  this.registerEvents();

  $element.data('likeLink', this);
};

LikeLink.prototype.destroy = function () {
  this.$element.off('click.likeLink');
};

LikeLink.prototype.registerEvents = function () {
  var $this = this;

  if (this.options.selector === null) {
    this.$element.on('click.likeLink', function(e) {
      $this.handleClickEvent(e, $(this));
    });
  } else {
    this.$element.on('click.likeLink', this.options.selector, function(e) {
      $this.handleClickEvent(e, $(this));
    });
  }
};

LikeLink.prototype.handleClickEvent = function (e, $element) {
  e.preventDefault();
  e.stopImmediatePropagation();

  if (typeof $element.attr('disabled') !== typeof undefined && $element.attr('disabled') !== false)
    return;

  var href = $element.attr('href');

  $element.attr('disabled', 'disabled');
  $.post( href )
    .done(function(data) {
      if (data.status === 'success') {
        $element.find('.like-count').text(data.likeCount);
        if (data.currentUserLikeIt)
          $element.find('.glyphicon').removeClass('glyphicon-heart-empty').addClass('glyphicon-heart');
        else
          $element.find('.glyphicon').removeClass('glyphicon-heart').addClass('glyphicon-heart-empty');
      }
    })
    .always(function() {
      $element.removeAttr('disabled');
      $element.blur();
    });
};

if ($.fn.likeLink == null) {
  $.fn.likeLink = function (options) {
    options = options || {};

    if (typeof options === 'object') {
      this.each(function () {
        var instanceOptions = $.extend(true, {}, options);

        var instance = new LikeLink($(this), instanceOptions);
      });

      return this;
    } else {
      throw new Error('Invalid arguments for LikeLink: ' + options);
    }
  };
}