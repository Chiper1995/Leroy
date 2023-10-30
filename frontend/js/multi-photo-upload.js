function PhotoUpload(elementId, namePrefix)
{
    var $this = this;

    this.elementId = '#' + elementId;

    this.onCompleteLoadImage = function (filename, fileurl, fullurl, func, id, description) {
        var photoIndex;
        var p = $($this.elementId);
        p.append('<div class="col-sm-3 col-md-3 photo photo-' + (photoIndex = p.find('div.photo').length) + '"><div class="thumbnail"></div></div>');
        var d = p.find('.photo-' + photoIndex + ' > .thumbnail');
        d.append('<input name="'+namePrefix+'[' + photoIndex + '][photo]" value="' + filename + '" type="hidden" class="photo-input" />');
        d.append('<input name="'+namePrefix+'[' + photoIndex + '][deleted]" value="0" type="hidden" class="photo-delete-input" />');
        d.append('<input name="'+namePrefix+'[' + photoIndex + '][id]" value="' + id + '" type="hidden" class="photo-id-input" />');
        d.append('<input name="'+namePrefix+'[' + photoIndex + '][description]" value="' + description + '" type="hidden" class="photo-description-input" />');
        d.append('<a class="im" rel="gallery_'+elementId+'" href="' + fileurl + '"><img src="' + fileurl + '"/></a>');
        d.append(' <div class="block caption text_review text-description-photo" data-text="">\n' +
            '       <div class="text content-description" id="myShowBlock">\n' +
                        '<div class="text-container">\n' +
                            '<div class="text hidden"></div>\n' +
                            '<a class="cursor-pointer hidden" onclick="toggleText(this)" data-label="Скрыть">Показать полностью</a>\n' +
                        '</div>\n' +
            '      </div>\n' +
            '     </div>');
        d.append('<div class="caption text-center"><a class="photo-edit" href="#"><i class="glyphicon glyphicon-pencil"></i><span class="edit-title">Добавить описание</span></a></div>');
        d.append('<div class="caption text-center"><a class="photo-delete" href="#"><i class="glyphicon glyphicon-trash"></i> Удалить</a></div>');
    };


    jQuery(document).ready(function () {
        $('body').on("click", $this.elementId+" .photo-edit", function () {
            var $target = $(this);
            var text = $target.parents('.photo').find('.text_review').attr('data-text');
            var url = $target.attr('data-url');
            bootbox.dialog({
                message: "<div class=\"description-container\">\n" +
                    " <div>\n" +
                    "  <textarea class=\"description-symbols\" placeholder='Введите описание' maxlength=\"400\" rows=\"5\" style=\"width: 100%\">" + text + "</textarea>\n" +
                    " </div>\n" +
                    " <div>\n" +
                    "Осталось <span class=\"count-symbols\">400</span> символов.\n" +
                    " </div>\n" +
                    "</div>" +
                    "<script>\n" +
                    "  var max = $('.description-symbols').attr('maxlength');\n" +
                    "  var textSymbols = $('.description-container').find('.count-symbols');\n" +
                    "  var text = $('.description-symbols').val();\n" +
                    "  textSymbols.html(max - text.length);\n" +
                    " $('.description-symbols').on('input', function (e) {\n" +
                    "  var max = $(this).attr('maxlength');\n" +
                    "  var textSymbols = $(this).parents('.description-container').find('.count-symbols');\n" +
                    "  var text = $(this).val();\n" +
                    "  textSymbols.html(max - text.length);\n" +
                    " });\n" +
                    "</script>",
                title: "Описание",
                buttons: {
                    success: {
                        label: "Сохранить",
                        className: "btn-primary btn-with-margin-right",
                        callback: function() {
                            var container =  $target.parents('.photo');
                            var description = $('.description-symbols').val();

                            container.find('.text_review').attr('data-text', description);
                            container.find('.photo-description-input').val(description);
                            container.find('.text.hidden').removeClass('hidden');

                            if (description.length === 0) {
                                container.find('.edit-title').html('Добавить описание');
                                container.find('.text').addClass('hidden');
                            } else {
                                container.find('.edit-title').html('Редактировать описание');
                            }
                            container.find('.content-description .text-container .text').html(description);
                            container.find('.content-description .text-container .text').attr('data-text', description);

                             if (description.length > 0) {
                                 var textNode = container.find('.content-description .text-container .text');
                                 if (textNode[0].clientHeight >= textNode[0].scrollHeight && textNode[0].scrollHeight < 24) {
                                     container.find('.text .cursor-pointer').addClass('hidden');
                                 } else {
                                     container.find('.text .cursor-pointer.hidden').removeClass('hidden');
                                 }
                             }
                            }
                    },
                    cancel: {
                        label: "Отмена",
                        className: "btn-default",
                        callback: function() {}
                    }
                }
            });
            return false;

        });

        $('body').on("click", $this.elementId+" .photo-delete", function () {
            var $this = $(this);

            bootbox.dialog({
                message: "Удалить это фото?",
                title: "Подтверждение",
                buttons: {
                    success: {
                        label: "Да",
                        className: "btn-primary btn-with-margin-right",
                        callback: function() {
                            $this.parents('div.thumbnail').find(".photo-delete-input").val(1);
                            $this.parents('div.photo').hide();
                        }
                    },
                    cancel: {
                        label: "Отмена",
                        className: "btn-default",
                        callback: function() {}
                    }
                }
            });
            return false;
        });
    });
}