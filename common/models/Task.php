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
 * Class Task
 * @package common\models
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $creator_id
 * @property string $deadline
 * @property User $creator
 * @property TaskPhoto[] $photos
 * @property TaskUser[] $families
 * @property Notification[] $linkedNotifications
 *
 * @mixin TaskQuery
 */
class Task extends ActiveRecord
{
    const STATUS_NEW = 1;
    const STATUS_IN_PROCESS = 2;
    const STATUS_ON_CHECK = 3;
    const STATUS_EXECUTED = 4;

    private $_oldFamilies = [];

    /**
     * @return ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'creator_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPhotos()
    {
        return $this->hasMany(TaskPhoto::className(), ['task_id' => 'id'])->inverseOf('task');
    }

    /**
     * @return ActiveQuery
     */
    public function getFamilies()
    {
        return $this->hasMany(TaskUser::className(), ['task_id' => 'id'])->inverseOf('task');
    }

    /**
     * @return ActiveQuery
     */
    public function getFamiliesLink()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
            ->via('families');
    }

    /**
     * @return ActiveQuery
     */
    public function getLinkedNotifications()
    {
        return $this->hasMany(Notification::className(), ['task_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getAllStatusNamesList()
    {
        return [
            self::STATUS_NEW => 'Новые',
            self::STATUS_IN_PROCESS => 'В процессе',
            self::STATUS_ON_CHECK => 'На проверке',
            self::STATUS_EXECUTED => 'Выполненные',
        ];
    }

    public function behaviors()
    {
        return [
            'TimestampBehavior' => [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 3, 'max' => 250],

            ['description', 'filter', 'filter' => 'trim'],

            ['deadline', 'date', 'format'=>'php:Y-m-d'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['name', 'description', 'deadline'];
        $scenarios['update'] = ['name', 'description', 'deadline'];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return array(
            'name' => 'Название',
            'description' => 'Описание',
            'creator_id' => 'Автор',
            'created_at' => 'Создана',
            'updated_at' => 'Последнее обновление',
            'deadline' => 'Срок выполнения',
        );
    }

    public static function find()
    {
        return new TaskQuery(get_called_class());
    }

    public function beforeDelete()
    {
        // Delete relations data
        if (!$this->isNewRecord) {
            foreach ($this->photos as $photo)
                $photo->delete();

            // ANDR Сделать оповещения об удалении задания, а удаление убрать
            // Удаляем оповещения
            //foreach ($this->families as $family) {
            //    Notification::findOne(['task_id'=>$this->id, 'init_user_id'=>$family->user_id])->delete();
            //}

            // Удаляем связи
            $this->unlinkAll('familiesLink', true);

            // Убираем оповещения
            foreach ($this->linkedNotifications as $notification) {
                $notification->setViewedAllUsers();
            }
        }
        return parent::beforeDelete();
    }

    public function afterFind(){
        $this->_oldFamilies = $this->families;
        return parent::afterFind();
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->creator_id = Yii::$app->user->id;
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        //$newIdList = ArrayHelper::getColumn($this->families, function($e){return $e->user_id;});
        $oldIdList = ArrayHelper::getColumn($this->_oldFamilies, function($e){return $e->user_id;});

        /*// Ищем удаленные
        foreach ($oldIdList as $id) {
            if (array_search($id, $newIdList) === false) {
                // Удаляем оповещения
                Notification::findOne(['task_id'=>$this->id, 'init_user_id'=>$id])->delete();
            }
        }*/

        // Ищем новые
        foreach ($this->families as $family) {
            if (array_search($family->user_id, $oldIdList) === false) {
                // Создаем оповещения для новых
                \Yii::$app->trigger(AppEvents::EVENT_TASK_ADDED, new Event(['sender' => ['task' => $this, 'user' => $family->user]]));
            }
        }
    }
}