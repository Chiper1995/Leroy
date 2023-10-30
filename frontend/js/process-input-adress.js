ymaps.ready(function () {
    var adressInput = 'adress-suggest';
    //инициализация поля ввода адреса
    var suggestView = new ymaps.SuggestView(adressInput);
    inputSuggest();

    $('#'+adressInput)
        .on('change', function() {
            $('#form-adress-group').removeClass('has-error');
            $('#form-error-message').hide();
            $('#form-error-message').html();

            var activeIndex = suggestView.state.get('activeIndex');
            var activeItem = (suggestView.state.get('items')[activeIndex])
                ? suggestView.state.get('items')[activeIndex].value : $('#'+adressInput).val();

            inputSuggest(activeItem);
        })
        .on('keyup', function() {
            if ($(this).val() === '') {
                $('#form-error-message').hide();
            }
        });

    function inputSuggest(req) {
        var myGeocoder = ymaps.geocode(req);
        myGeocoder.then(function(res) {
            var obj = res.geoObjects.get(0), error, hint;

            var coords = obj.geometry.getCoordinates();
            $('#form-latitude').val(coords[0]);
            $('#form-longitude').val(coords[1]);

            var city = obj.getLocalities()[0];
            $('#profile-city_id').select2('val', $('#profile-city_id option:contains("' + city + '")').val());

            checkWrongAdress(obj, error, hint);
        });
    }

    function checkWrongAdress(obj, error, hint) {
        if (obj) {
           switch (obj.properties.get('metaDataProperty.GeocoderMetaData.precision')) {
               case 'exact':
                   break;
               case 'number':
               case 'near':
               case 'range':
                   error = 'Неточный адрес, требуется уточнение';
                   hint = 'Уточните номер дома';
                   break;
               case 'street':
                   error = 'Неполный адрес, требуется уточнение';
                   hint = 'Уточните номер дома';
                   break;
               case 'other':
               default:
                   error = 'Неточный адрес, требуется уточнение';
                   hint = 'Уточните адрес';
           }
        } else {
           error = 'Адрес не найден';
           hint = 'Уточните адрес';
        }

        if (hint) {
            $('#form-adress-group').addClass('has-error');
            $('#form-error-message').show();
            $('#form-error-message').html(hint);
        }
    }
});