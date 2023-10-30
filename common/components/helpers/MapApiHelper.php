<?php

namespace common\components\helpers;

use yii\helpers\Url;
use common\models\User;

class MapApiHelper
{
    const ENTRY_POINT_GEOCODE = 'https://geocode-maps.yandex.ru/1.x/';


    //формирует json-данные для отправки на фронт для карты в зависимости от города
    public static function formLocationsCityData($data)
    {
        $locations = [
            'type' => 'FeatureCollection',
            'features' => []
        ];

        $i = 0;
        foreach ($data as $user) {
            if ($user->status == User::STATUS_DELETED) {
                continue;
            }

            if (!empty($adress = $user->homeAdress)) {
                $coords = [$adress->longitude, $adress->latitude];
            } else if (!empty($adress = $user->homeRepairLocation)) {
                $coords = [$adress->latitude, $adress->longitude];
            } else {
                continue;
            }

            $fio = $user->fio ? $user->fio : 'Семья';
            $url =  Url::toRoute(['family-view', 'id'=>$user->id]);
            $hintContent = '<a href="' . $url . '" target="_blank">' . $fio
                            . '</a>  ' . $user->phone;
            $balloonContent =  $fio
                            . '<br>' . $user->phone
                            . '<br>' . $adress->adress
                            . '<br> <a href="' . $url . '" target="_blank">Перейти на страницу семьми</a>';


            $point = self::formLocationPoint($i, $coords, $hintContent, $balloonContent);
            array_push($locations['features'], $point);
            $i++;
        }

        return $locations;
    }

    //формирование одной точки для общего массива
    protected static function formLocationPoint($id, $coords, $hint, $balloon)
    {
        $point = [
            'type' => 'Feature',
            'id' => $id,
            'geometry' => [
                'type' => 'Point',
                'coordinates' => $coords,
            ],
            'properties' => [
                'hintContent' => $hint,
                'balloonContent' => $balloon,
            ],
        ];

        return $point;
    }


    //извлечение координат с ответа по апи карт
    public static function getCoords($adress)
    {
        $geoCode = self::geocoding($adress);
        $geoCode = json_decode($geoCode, true);

        if (!isset($geoCode['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos']))
            return null;

        $point = $geoCode['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];
        $point = explode(' ', $point);
        return $point;
    }

    //получение города из адреса
    public static function getCity($adress)
    {
        $geoCode = self::geocoding($adress);
        $geoCode = json_decode($geoCode, true);

        if (!isset($geoCode['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']
            ['GeocoderMetaData']['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']
            ['Locality']['LocalityName'])) {
            return null;
        }

        return $geoCode['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']
            ['GeocoderMetaData']['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']
            ['Locality']['LocalityName'];
    }

    //получение правильного адреса
    public static function getRightAdress($adress)
    {
        $geoCode = self::geocoding($adress);
        $geoCode = json_decode($geoCode, true);

        if (!isset($geoCode['response']['GeoObjectCollection']['featureMember'][0]
            ['GeoObject']['metaDataProperty']['GeocoderMetaData']['text'])) {
            return $adress;
        }

        return $geoCode['response']['GeoObjectCollection']['featureMember'][0]
            ['GeoObject']['metaDataProperty']['GeocoderMetaData']['text'];
    }

    //получение объекта геодинга с яндекс апи
    protected static function geocoding($adress)
    {
        $header_data = [];

        $post_data = [
            'apikey' => \Yii::$app->params['apikey'],
            'geocode' => $adress,
            'format' => 'json'
        ];

        $url = self::ENTRY_POINT_GEOCODE;

        return self::send($url, $header_data, $post_data);
    }


    //отправка запроса и возвращение ответа
    protected static function send($url, $header_data, $post_data = 0)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data);

        if (!empty($post_data)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }

        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }
}
