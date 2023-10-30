ymaps.ready(function () {
    var adressInput = '.adress-suggest';
    //инициализация поля ввода адреса
    $(adressInput).each(function( index, value ) {
        var id = $(value).attr('id');
        var suggestView = new ymaps.SuggestView(id);

        $('#'+id).on('change', function() {
            var activeIndex = suggestView.state.get('activeIndex');
            var activeItem = (suggestView.state.get('items')[activeIndex])
                ? suggestView.state.get('items')[activeIndex].value : $('#'+id).val();

        });
    });
});