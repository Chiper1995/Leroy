<?php
namespace common\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class UserLocation extends ActiveRecord
{
    public function behaviors()
    {
        return [
            'TimestampBehavior' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => false,
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public function getCity()
    {
        return $this->hasOne(User::className(), ['id' => 'city_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getJournal()
    {
        return $this->hasOne(Journal::className(), ['id' => 'journal_id']);
    }

    public function rules()
    {
        return [
            [['adress', 'latitude', 'longitude'], 'required', 'on' => 'create'],
            [['adress', 'latitude', 'longitude'], 'required', 'on' => 'update'],
        ];
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['adress', 'flat', 'latitude', 'longitude'];
        $scenarios['update'] = ['adress', 'flat', 'latitude', 'longitude'];
        $scenarios['additional'] = [];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'journal_id' => 'Дневник',
            'city_id' => 'Город',
            'user_id' => 'Пользователь',
            'is_home_adress' => 'Домашний адрес?',
            'adress' => 'Адрес ремонта',
            'flat' => 'Квартира',
            'latitude' => 'Широта',
            'longitude' => 'Долгота',
        );
    }
}
