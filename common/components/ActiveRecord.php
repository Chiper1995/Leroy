<?php

namespace common\components;

use yii\behaviors\TimestampBehavior;
use yii\caching\DbDependency;

/**
 * Class ActiveRecord
 * @package common\components
 *
 * @mixin TimestampBehavior
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'TimestampBehavior' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => false,
            ],
        ];
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['create'] = [];
        $scenarios['update'] = [];
        $scenarios['view'] = [];
        return $scenarios;
    }

    public static function getCacheDependency()
    {
        return new DbDependency(['sql'=>"SELECT MAX(updated_at), COUNT(*) FROM ".static::tableName()]);
    }

    public function touch()
    {
        $this->getBehavior('TimestampBehavior')->touch('updated_at');
    }
}