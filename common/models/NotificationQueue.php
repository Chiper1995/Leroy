<?php
namespace common\models;
use common\components\ActiveRecord;
/**
 * Class NotificationQueue
 * @package common\models
 *
 * @property integer $id
 * @property integer $comment_id
 * @property integer $updated_at
 * @property string $success
 * @property integer $user_id
 * @property integer $task_id
 */
class NotificationQueue extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%notification_queue}}';
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'comment_id' => 'Коммент',
            'success' => 'Флаг обработки(Y/N)',
            'updated_at' => 'Последнее обновление',
            'user_id' => 'ID пользователя',
            'task_id' => 'ID задачи'
        );
    }

    public function attributes()
    {
        return [
            'id',
            'comment_id',
            'success',
            'updated_at',
            'user_id',
            'task_id'
        ];
    }

}
