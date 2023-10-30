<?php
namespace common\models;

use common\components\ActiveRecord;
use common\events\AppEvents;
use common\models\notifications\Notification;
use Yii;
use yii\base\Event;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class DialogMessage
 * @package common\models
 *
 * @property integer $id
 * @property integer $dialog_id
 * @property integer $user_id
 * @property string $message
 * @property integer $created_at
 * @property User $user
 * @property Dialog $dialog
 */
class DialogMessage extends ActiveRecord
{
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
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDialog()
    {
        return $this->hasOne(Dialog::className(), ['id' => 'dialog_id'])->inverseOf('messages');
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['message', 'filter', 'filter' => 'trim'],
			['message', 'filter', 'filter' => function ($value) {
				return \yii\helpers\HtmlPurifier::process($value);
			}],
            ['message', 'required'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['message'];
        $scenarios['update'] = ['message'];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return array(
            'message'=>'Сообщение'
        );
    }

}