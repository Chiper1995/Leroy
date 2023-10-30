<?php
namespace common\models;

use common\components\ActiveRecord;
use common\events\AppEvents;
use common\models\notifications\Notification;
use common\rbac\Rights;
use Yii;
use yii\base\Event;
use yii\db\ActiveQuery;

/**
 * Class Visit
 * @package common\models
 *
 * @property integer $id
 * @property integer $date
 * @property integer $time
 * @property integer $status
 * @property integer $user_id
 * @property integer $creator_id
 * @property string $description
 * @property integer $updated_at
 * @property integer $points
 *
 * @property User $user
 * @property User $creator
 * @property Notification[] $linkedNotifications
 *
 * @mixin VisitQuery
 */
class Visit extends ActiveRecord
{
    const STATUS_ON_AGREEMENT = 1;
    const STATUS_CANCELED = 2;
    const STATUS_AGREED = 3;
    const STATUS_TIME_EDITED = 4;

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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getLinkedNotifications()
    {
        return $this->hasMany(Notification::className(), ['visit_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getAllStatusNamesList()
    {
        return [
            self::STATUS_ON_AGREEMENT => 'На согласовании',
            self::STATUS_CANCELED => 'Отменен',
            self::STATUS_AGREED => 'Согласован',
            self::STATUS_TIME_EDITED => 'Время изменено',
        ];
    }

    /**
     * @return array
     */
    public static function getAllTimeNamesList()
    {
        return [
            1 => '09:00 - 11:00',
            2 => '11:00 - 13:00',
            3 => '13:00 - 15:00',
            4 => '15:00 - 17:00',
            5 => '17:00 - 19:00',
            6 => '19:00 - 21:00',
        ];
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['date', 'required'],
            ['date', 'date', 'format'=>'php:Y-m-d'],

            ['description', 'filter', 'filter' => 'trim'],

            ['user_id', 'required'],
            ['user_id', 'number', 'integerOnly' => true],

            ['time', 'required'],
            ['time', 'number', 'integerOnly' => true],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['date', 'description', 'user_id', 'time'];
        $scenarios['update'] = ['date', 'description', 'user_id', 'time'];
        $scenarios['agreement'] = ['status', 'time'];
        $scenarios['agreementTime'] = ['status'];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return array(
            'date' => 'Дата',
            'time' => 'Время',
            'status' => 'Статус',
            'user_id' => 'Семья',
            'creator_id' => 'Автор',
            'description' => 'Описание',
            'updated_at' => 'Последнее обновление',
        );
    }

    public static function find()
    {
        return new VisitQuery(get_called_class());
    }

    public function beforeDelete()
    {
        if (!$this->isNewRecord) {
            Yii::$app->trigger(AppEvents::EVENT_VISIT_CANCELED, new Event(['sender' => $this]));

            // Убираем оповещения
            foreach ($this->linkedNotifications as $notification) {
                $notification->setViewedAllUsers();
            }
        }
        return parent::beforeDelete();
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            // Отправляем на согласование
            $this->status = self::STATUS_ON_AGREEMENT;
            $this->creator_id = Yii::$app->user->id;
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if (($insert) or (isset($changedAttributes['status']))) {
            if ($this->status == self::STATUS_ON_AGREEMENT) {
                Yii::$app->trigger(AppEvents::EVENT_VISIT_ON_AGREEMENT, new Event(['sender' => $this]));
            }
            else if ($changedAttributes['status'] == self::STATUS_ON_AGREEMENT) {
                if ($this->status == self::STATUS_AGREED) {
                    Yii::$app->trigger(AppEvents::EVENT_VISIT_AGREED_FAMILY, new Event(['sender' => $this]));
                }
                else if ($this->status == self::STATUS_TIME_EDITED) {
                    Yii::$app->trigger(AppEvents::EVENT_VISIT_TIME_EDITED_FAMILY, new Event(['sender' => $this]));
                }
                else if ($this->status == self::STATUS_CANCELED) {
                    Yii::$app->trigger(AppEvents::EVENT_VISIT_CANCELED_FAMILY, new Event(['sender' => $this]));
                }
            }
            else if ($changedAttributes['status'] == self::STATUS_TIME_EDITED) {
                if ($this->status == self::STATUS_AGREED) {
                    Yii::$app->trigger(AppEvents::EVENT_VISIT_AGREED, new Event(['sender' => $this]));
                }
                else if ($this->status == self::STATUS_CANCELED) {
                    Yii::$app->trigger(AppEvents::EVENT_VISIT_CANCELED, new Event(['sender' => $this]));
                }
            }
            else if ($this->status == self::STATUS_CANCELED) {
                Yii::$app->trigger(AppEvents::EVENT_VISIT_CANCELED, new Event(['sender' => $this]));
            }
        }
    }

    /**
     * Список семей
     * @return mixed
     */
    public static function getFamiliesList()
    {
        $cityId = null;
        if (\Yii::$app->user->can(Rights::SHOW_IN_MY_CITY_VISITS)) {
            /**@var User $user*/
            $user = \Yii::$app->user->identity;
            $cityId = $user->getCities()->select('id')->column();
        }

        return User::getFamiliesList($cityId);
    }
}