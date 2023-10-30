FavoriteLink = function ($element, options) {
    if ($element.data('favoriteLink') != null) {
        $element.data('favoriteLink').destroy();
    }

    this.$element = $element;
    this.options = $.extend({}, {
        selector: null
    }, options);

    this.registerEvents();

    $element.data('favoriteLink', this);
};

FavoriteLink.prototype.destroy = function () {
    this.$element.off('click.favoriteLink');
};

FavoriteLink.prototype.registerEvents = function () {
    var $this = this;

    if (this.options.selector === null) {
        this.$element.on('click.favoriteLink', function(e) {
            $this.handleClickEvent(e, $(this));
        });
    } else {
        this.$element.on('click.favoriteLink', this.options.selector, function(e) {
            $this.handleClickEvent(e, $(this));
        });
    }
};

FavoriteLink.prototype.handleClickEvent = function (e, $element) {
    e.preventDefault();
    e.stopImmediatePropagation();

    if (typeof $element.attr('disabled') !== typeof undefined && $element.attr('disabled') !== false)
        return;

    var href = $element.attr('href');
    $element.attr('disabled', 'disabled');
    $.post( href )
        .done(function(data) {
            if (data.status === 'success') {
                if (data.currentUserFavoriteIt)
                    $element.find('.glyphicon').removeClass('glyphicon-star-empty').addClass('glyphicon-star');
                else {
                    $element.find('.glyphicon').removeClass('glyphicon-star').addClass('glyphicon-star-empty');
                    console.log($element.parents('div.col-md-12').children().length);
                    if ($element.parents('div.col-md-12').children().length === 1){
                        $element.parents('.row').children('.content-container').removeClass('hidden');
                    }
                    if ($element.hasClass('js-remove-favorite')) {
                        $element.parents('.item').remove();
                    }
                }
            }
        })
        .always(function() {
            $element.removeAttr('disabled');
            $element.blur();
        });
};

if ($.fn.favoriteLink == null) {
    $.fn.favoriteLink = function (options) {
        options = options || {};

        if (typeof options === 'object') {
            this.each(function () {
                var instanceOptions = $.extend(true, {}, options);

                var instance = new FavoriteLink($(this), instanceOptions);
            });

            return this;
        } else {
            throw new Error('Invalid arguments for FavoriteLink: ' + options);
        }
    };
}