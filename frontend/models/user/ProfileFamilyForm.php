<?php
namespace frontend\models\user;

use yii\base\Model;
use common\models\UserLocation;
use common\models\City;
use yii\helpers\ArrayHelper;
use common\components\helpers\MapApiHelper;

class ProfileFamilyForm extends Model
{
    public $adress;
    public $adresses = [];

    public function attributeLabels()
    {
        return [
            'adress' => 'Адрес',
            'addresses' => 'Дополнительные адресы',
        ];
    }

    public function fillFromUser($user)
    {
        $this->adress = isset($user->homeAdress->adress) ? $user->homeAdress->adress : null;
        $this->adresses = isset($user->repairLocations) ? $user->repairLocations: null;

        foreach ($this->adresses as $adress) {
            $adress->scenario = 'additional';
        }
    }

    public function saveFromUser($user, $request)
    {
        $this->load($request);
        if (!isset($request['UserLocation'])) {
            return true;
        }
        $this->adresses = ArrayHelper::getColumn($request['UserLocation'], 'adress');
        $homeAdress = $user->homeAdress;
        #echo $user->homeAdress;
        #print_r($user);   
        #$homeAdress->adress = $this->adress;
        #$homeAdress->adress = "";
        #$homeAdress->save();

        UserLocation::deleteAll(['user_id' => $user->id, 'is_home_adress' => false]);

        foreach ($this->adresses as $adress) {
            if (empty($adress)) {
                continue;
            }
            $coords = MapApiHelper::getCoords($adress);

            $cityId = $user->city->id;
            $cityName = MapApiHelper::getCity($adress);
            if (!empty($cityName)) {
                $city = City::find()->where(['name' => $cityName])->one();
                if (!empty($city)) {
                    $cityId = $city->id;
                }
            }
            $adress = MapApiHelper::getRightAdress($adress);

            $userLocation = new UserLocation();
            $userLocation->user_id = $user->id;
            $userLocation->adress = $adress;
            $userLocation->is_home_adress = false;
            $userLocation->latitude = $coords[0];
            $userLocation->longitude = $coords[1];
            $userLocation->city_id = $cityId ? $cityId : null;
            $userLocation->save();
        }

        return true;
    }

    public function rules()
    {
        return [
            ['adress', 'required'],
            ['adress', 'string'],
        ];
    }

}
