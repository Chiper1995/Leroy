<?php

namespace common\models;

use common\components\ActiveRecord;

/**
 * Class UserSubscription
 * @package common\models
 *
 * @property integer $user_id
 * @property integer $to_user_id
 */
class UserSubscription extends ActiveRecord
{
    public function behaviors()
    {
        return [];
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['user_id', 'required'],
            ['user_id', 'integer'],

            ['to_user_id', 'required'],
            ['to_user_id', 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'Пользователь',
            'to_user_id' => 'Подписан на пользователя',
        ];
    }
}