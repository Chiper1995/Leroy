<?php
namespace common\models;

use common\models\notifications\Notification;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class TaskUser
 * @package common\models
 *
 * @property integer $task_id
 * @property integer $user_id
 * @property integer $journal_id
 * @property integer $status
 * @property Journal $journal
 * @property Task $task
 * @property User $user
 * @property Notification[] $linkedNotifications
 */
class TaskUser extends ActiveRecord
{
    const STATUS_ACTIVE = 0;
    const STATUS_REFUSED = 1;
    const STATUS_EXPIRED = 2;

    /**
     * @return ActiveQuery
     */
    public function getJournal()
    {
        return $this->hasOne(Journal::className(), ['id' => 'journal_id']);
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
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getLinkedNotifications()
    {
        return $this->hasMany(Notification::className(), ['task_id' => 'task_id', 'init_user_id' => 'user_id']);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['task_id', 'number', 'integerOnly' => true],

            ['user_id', 'required'],
            ['user_id', 'number', 'integerOnly' => true],

            ['journal_id', 'number', 'integerOnly' => true],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['task_id', 'user_id', 'journal_id'];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return array(
            'task_id' => 'Задание',
            'user_id' => 'Пользователь',
            'journal_id' => 'Запись дневника',
            'status' => 'Статус',
        );
    }

    public static function find()
    {
        return new TaskUserQuery(get_called_class());
    }
}