function PhotoUpload(elementId, namePrefix)
{
    var $this = this;

    this.elementId = '#' + elementId;

    this.onCompleteLoadImage = function (filename, fileurl) {
        var photoIndex;
        var p = $($this.elementId);
        p.append('<div class="col-sm-3 col-md-3 photo photo-' + (photoIndex = p.find('div.photo').length) + '"><div class="thumbnail"></div></div>');
        var d = p.find('.photo-' + photoIndex + ' > .thumbnail');
        d.append('<input name="'+namePrefix+'[' + photoIndex + '][photo]" value="' + filename + '" type="hidden" class="photo-input" />');
        d.append('<input name="'+namePrefix+'[' + photoIndex + '][deleted]" value="0" type="hidden" class="photo-delete-input" />');
        d.append('<a class="im" rel="gallery_'+elementId+'" href="' + fileurl + '"><img src="' + fileurl + '"/></a>');
        d.append('<div class="caption text-center"><a class="photo-edit" href="#"><i class="glyphicon glyphicon-edit"></i> Добавить описание</a></div>');
        d.append('<div class="caption text-center"><a class="photo-delete" href="#"><i class="glyphicon glyphicon-trash"></i> Удалить</a></div>');

    };

    jQuery(document).ready(function () {
        $('body').on("click", $this.elementId+" .photo-edit", function () {
          alert('adsad');
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