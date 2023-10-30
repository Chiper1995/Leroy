<?php

namespace console\controllers;

use yii\console\Controller;
use common\components\helpers\MapApiHelper;
use common\models\User;
use common\models\UserLocation;

class MapController extends Controller
{
    public function actionConvertAdress()
    {
        $users = User::find()->all();
        foreach ($users as $user) {
            if (!empty($user->homeAdress)) continue;

            $city = $user->city;
            if (empty($city)) continue;
            if (empty($user->address)) continue;

            $coords = MapApiHelper::getCoords($city->name . ' ' . $user->address);

            if (empty($coords)) continue;

            $userLocation = new UserLocation();
            $userLocation->user_id = $user->id;
            $userLocation->adress = $city->name . ' ' . $user->address;
            $userLocation->is_home_adress = true;
            $userLocation->latitude = $coords[0];
            $userLocation->longitude = $coords[1];
            $userLocation->city_id = $city->id;
            $userLocation->save();
        }
    }
}
