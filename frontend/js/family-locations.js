//Фронтенд-часть виджета FamilyLocations
ymaps.ready(function () {
    var selectCity = "select-city";     //id инпута, на который вешается обработка события смены города

    var map = init();
    addFamilyLocations('Москва');

    $("#"+selectCity).on('change', function() {
        //получить название города
        var city = $("#select2-"+selectCity+"-container").html();
        changeCity(city);
    });


    function init(center, zoom) {
        if (center === undefined) {
            center = [55.76, 37.64];
        }

        if (zoom === undefined) {
            zoom = 12;
        }

        var map = new ymaps.Map("YMaps", {
            center: center,
            zoom: zoom
        });

        return map;
    }

    function addFamilyLocations(city) {
        var objectManager = new ymaps.ObjectManager();
        map.geoObjects.add(objectManager);

        jQuery.ajax({
            type: "POST",
            url: "/user/city-locations",
            data: "city="+city,
            success: function(json){
                console.log(json);
                objectManager.add(json);
            }
        });
    }

    function changeCity(city) {
        var myGeocoder = ymaps.geocode(city);
        myGeocoder.then(function(res) {
            var obj = res.geoObjects.get(0);

            var coords = obj.geometry.getCoordinates();
            rebuild(coords);

            addFamilyLocations(city);
        });
    }

    function rebuild(center, zoom) {
        if (zoom === undefined) {
            zoom = 13;
        }
        map.setCenter(center, zoom);
        map.geoObjects.removeAll();
    }
});