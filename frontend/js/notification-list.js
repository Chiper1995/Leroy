NotificationList = function ($element, options) {
  if ($element.data('notificationList') != null) {
    $element.data('notificationList').destroy();
  }

  this.$element = $element;
  this.options = $.extend({}, {

  }, options);

  this.registerEvents();

  $element.data('notificationList', this);
};

NotificationList.prototype.destroy = function () {
  this.$element.off('click.notificationList');
};

NotificationList.prototype.registerEvents = function () {
  var $this = this;

  this.$element.on('click.notificationList', '.notification-more a', function(e) {
    $this.handleClickEvent(e, $(this));
  });
  this.$element.on('click.notificationList', '.notification-close', function(e) {
    $this.handleCloseClickEvent(e, $(this));
  });
};

NotificationList.prototype.handleClickEvent = function (e, $element) {
  e.preventDefault();
  e.stopImmediatePropagation();

  if (typeof $element.attr('disabled') !== typeof undefined && $element.attr('disabled') !== false)
    return;

  var href = $element.attr('href');
  var self = this;

  $element.attr('disabled', 'disabled');
  $.post( href )
    .done(function(data) {
      self.$element.find('.notification-more').remove();
      self.$element.append(data);
    })
    .always(function() {
      $element.removeAttr('disabled');
    });
};

NotificationList.prototype.handleCloseClickEvent = function (e, $element) {
  e.preventDefault();
  e.stopImmediatePropagation();

  if (typeof $element.attr('disabled') !== typeof undefined && $element.attr('disabled') !== false)
    return;

   var href = $element.attr('href');
   var countNotification = ((($element.parents('li.notification-bell-link')).children('a.dropdown-toggle')).children('span.notification-badge'));
   var count = $element.parents('li.notification')[0].dataset.count;
   if (count)
   {
       if (countNotification.html() - count === 0)
       {
           $element.parents('li.notification-bell-link').children('ul.notification-list-container').css( "display", "none" );
           countNotification.removeClass('badge-danger');
       }
   }
   else
   {
       if (countNotification.html() - 1 === 0)
       {
           $element.parents('li.notification-bell-link').children('ul.notification-list-container').css( "display", "none" );
           countNotification.removeClass('badge-danger');
       }
   }


  $element.attr('disabled', 'disabled');
  $.post( href )
      .done(function(data) {
          var count = $element.parents('li.notification')[0].dataset.count;
        if (count)
          countNotification.text(countNotification.html()-count);
        else
          countNotification.text(countNotification.html()-1);

        $element.parents('li.notification').remove();

      })
      .always(function() {
        $element.removeAttr('disabled');
      });
};

if ($.fn.notificationList == null) {
  $.fn.notificationList = function (options) {
    options = options || {};

    if (typeof options === 'object') {
      this.each(function () {
        var instanceOptions = $.extend(true, {}, options);

        var instance = new NotificationList($(this), instanceOptions);
      });

      return this;
    } else {
      throw new Error('Invalid arguments for NotificationList: ' + options);
    }
  };
}
