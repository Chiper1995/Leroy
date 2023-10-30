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
            $('.parent-comment div').html('<b>'+user+'</b><br/>').append(document.createTextNode(comment));
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

        $('body').on('click', '.edit', function(){
            var id = $(this).attr('data-id');
            var commentId = '#comment-' + id;
            var formId = '#form-' + id;
            var text = $(commentId).find('p').text();
            var url = '';

            var editNode = '<div class="media-body newnode">';
            editNode += '<form id="form-' + id + '">';
            editNode += '<textarea id="text" class="form-control" rows="3">';
            editNode += text;
            editNode += ' </textarea>';
            editNode += '<input type="submit" value="Сохранить">';
            editNode += '</form></div>';

            $(commentId).find('.media-body').hide();
            $(commentId).find('.media-left').after(editNode);

            $(formId).submit(function(event) {
                event.preventDefault();
                var newText = $(this).find('#text').val();

                $('#edit-comment').val(id);
                $('#edit-comment-form').children("input[name*='comment_text']").val(newText);
                $('#edit-comment').click();

                $(commentId).find('.media-body').each(function() {
                    if ($(this).hasClass("newnode")) {
                        $(this).detach();
                    } else {
                        $(this).children('p').html(newText);
                        $(this).toggle();
                    }

                });
            });

            return true;
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

	$('#add-comment').on('click',function(){
        	if($('#comment_content').val() != ""){
            		$('#add-comment').hide();
         	} else {
             		$('#add-comment').show();
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
