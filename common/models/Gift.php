<?php
/**
 * Created by PhpStorm.
 * User: arshatilov
 * Date: 09.06.2018
 * Time: 12:11
 */

namespace common\models;

use common\components\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Class Gift
 * @package common\models
 *
 * @property integer $id
 * @property integer $from_family_id
 * @property integer $to_family_id
 * @property integer $journal_id
 * @property integer $points
 * @property integer $created_at
 */
class Gift extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';

    public function behaviors()
    {
        return [
            'TimestampBehavior' => [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['from_family_id', 'required'],
            ['from_family_id', 'integer'],

            ['to_family_id', 'required'],
            ['to_family_id', 'integer'],

            ['journal_id', 'required'],
            ['journal_id', 'integer'],

            ['points', 'required'],
            ['points', 'integer'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[static::SCENARIO_CREATE] = ['from_family_id', 'to_family_id', 'journal_id', 'points'];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'from_family_id' => 'От пользователя',
            'to_family_id' => 'Кому',
            'journal_id' => 'Пост',
            'points' => 'Баллы',
        ];
    }
}