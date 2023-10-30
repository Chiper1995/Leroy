<?php


namespace common\models;


use common\components\ActiveRecord;
use Yii;
use yii\base\Event;
use yii\behaviors\TimestampBehavior;

class BasePoints extends ActiveRecord
{
    /**
     * @var string тип события
     */
    public $event;

    /**
     * {@inheritDoc}}
     */
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
     * {@inheritDoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert && isset($this->points)) {
            Yii::$app->trigger($this->event, new Event(['sender' => $this]));
        }
    }
}
