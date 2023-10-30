<?php
namespace common\models;
use common\components\ActiveRecord;
use common\models\notifications\Notification;

/**
 * Class UserNotification
 * @package common\models
 *
 * @property integer $user_id
 * @property integer $notification_id
 * @property integer $viewed
 */

class UserNotification extends ActiveRecord
{
    public static function primaryKey(){
        return array('user_id', 'notification_id');
    }

    public static function tableName()
    {
        return '{{%user_notification}}';
    }

    public function attributeLabels()
    {
        return array(
            'user_id' => 'ID пользователя',
            'notification_id' => 'ID уведомления',
            'viewed' => 'Флаг'
        );
    }

    public function attributes()
    {
        return [
            'user_id',
            'notification_id',
            'viewed',
        ];
    }
}
