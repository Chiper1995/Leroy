jQuery(document).ready(function () {

    (function($){
        window.isScrolledIntoView = function (elem)
        {
            var _elem = $(elem);
            if (_elem.length == 0) return true;

            var _window = $(window);

            var docViewTop = _window.scrollTop();
            var docViewBottom = docViewTop + _window.height();

            var elemTop = _elem.offset().top;
            var elemBottom = elemTop + _elem.height();

            return ((docViewTop < elemTop) && (docViewBottom > elemBottom));
        };

        window.setParentComment = function (el) {
            var user = $(el).parent().find('span').text();
            var comment = $(el).parent().nextAll('p:first').text();
            $('.parent-comment div').html('<b>'+user+'</b><br/>'+comment);
            $('#comment_parent_id').val($(el).attr('data-id'));
            $('.parent-comment').show();
        };

        $('body').on('click', '.reply', function(){
            setParentComment(this);
            return true;
        });

        $('body').on('click', '.cancel-reply', function(){
            $('.parent-comment div').html('');
            $('#comment_parent_id').val('');
            $('.parent-comment').hide();
            return false;
        });

        $('body').on('click', '.delete', function() {
            var $this = $(this);

            bootbox.dialog({
                message: "Удалить этот комментарий?",
                title: "Подтверждение",
                buttons: {
                    success: {
                        label: "Да",
                        className: "btn-primary btn-with-margin-right",
                        callback: function() {
                            $('#del-comment').val($this.attr('data-id'));
                            var m = $this.parents('.media:first');
                            m.addClass('deleting');
                            setTimeout(function(){ $('#del-comment').click(); m.hide();}, 500);
                        }
                    },
                    cancel: {
                        label: "Отмена",
                        className: "btn-default",
                        callback: function() {}
                    },
                }
            });


            return false;
        });

       $('body').on('click', '#add-comment', function() {
                	if($('#comment_content').val() !== ""){
                       	 $('#add-comment').hide();
                	}
        	});
        //
        var h = location.hash;
        if (h.indexOf('comment-')!=-1) {
            $(h).addClass("showed");
            setTimeout(function(){$(h).removeClass("showed");}, 1000);
        }
    })(jQuery);

});
