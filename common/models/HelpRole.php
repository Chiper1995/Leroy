<?php

namespace common\models;

use common\components\ActiveRecord;

/**
 * Class HelpRole
 * @package common\models
 *
 * @property integer $help_id
 * @property string $role
 */
class HelpRole extends ActiveRecord
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
            ['help_id', 'required'],
            ['help_id', 'integer'],

            ['role', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'help_id' => 'Страница справки',
            'role' => 'Роль',
        ];
    }
}