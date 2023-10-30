function PhotoUpload(elementPhotoId, elementInputId)
{
    var $this = this;

    this.photoId = '#' + elementPhotoId;
    this.InputId = '#' + elementInputId;

    this.onCompleteLoadImage = function (filename, fileurl) {
        $($this.photoId).find('img').attr('src', fileurl);
        $($this.InputId).val(filename);
    };
}