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
use yii\helpers\StringHelper;

/**
 * Class Journal
 * @package common\models
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $subject
 * @property string $content
 * @property integer $status
 * @property integer $visibility
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $points
 * @property string $return_reason
 * @property string $return_photo_reason
 * @property string $version_token
 * @property integer $view
 * @property integer $published_id
 * @property string $preparation_purchase
 * @property string $store_selection
 * @property string $assessment_product
 * @property string $conclusion
 * @property string $advice
 * @property string $additional_information
 *
 * @property User $user
 * @property JournalPhoto[] $photos
 * @property JournalGoods[] $goods
 * @property JournalCheckPhoto[] $checkPhotos
 * @property Task $task
 * @property JournalComment[] $comments
 * @property TaskUser $taskUser
 * @property Goods[] $goodsLink
 * @property Notification[] $linkedNotifications
 * @property JournalType[] $journalTypes
 * @property JournalTag[] $journalTags
 * @property RoomRepair[] $repairRooms
 * @property WorkRepair[] $repairWorks
 * @property User[] $likeUsers
 *
 * @mixin JournalQuery
 */
class Journal extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_CHECK = 'check';
    const SCENARIO_RETURN_TO_EDIT = 'return-to-edit';
    const SCENARIO_RETURN_PHOTO_TO_EDIT = 'return-photo-to-edit';

    const STATUS_PUBLISHED = 3;
    const STATUS_ON_CHECK = 2;
    const STATUS_DRAFT = 1;

    const VISIBILITY_JUST_ME = 1;
    const VISIBILITY_FOR_ALL = 2;

    const FAVORITE_POST = 1;

    const REASON_DEFAULT = 'не указана';

    private $_giftPoints;

    public static function getVisibilityList($isInTask = false)
    {
        $result = [];
        $userRole = Yii::$app->user->identity->role;
        if($isInTask && $userRole == User::ROLE_FAMILY){
            $result = [
                self::VISIBILITY_JUST_ME => 'Только для меня',
            ];
        }else{
            $result = [
                self::VISIBILITY_JUST_ME => 'Только для меня',
                self::VISIBILITY_FOR_ALL => 'Для всех, отображается в ленте',
            ];
        }
        return $result;
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return int
     */
    public function getPublishedId()
    {
        return $this->published_id;
    }

    /**
     * Возвращает ФИО сотрудника опубликовавшего запись (журнал)
     * @return mixed|string
     */
    public function getPublishedName()
    {
        $publishedName = User::find()
            ->where(['id' => $this->published_id])
            ->one();
        return $publishedName->fio;
    }

    /**
     * @return ActiveQuery
     */
    public function getPhotos()
    {
        return $this->hasMany(JournalPhoto::className(), ['journal_id' => 'id'])->inverseOf('journal');
    }

    /**
     * @return array|JournalPhoto[]
     */
    public function getPublishedPhotos()
    {
        return array_filter(
            $this->photos,
            function (JournalPhoto $photo) {
                return $photo->status == JournalPhoto::STATUS_PUBLISHED;
            }
        );
    }

    /**
     * @return array|JournalPhoto[]
     */
    public function getOnCheckPhotos()
    {
        return array_filter(
            $this->photos,
            function (JournalPhoto $photo) {
                return $photo->status == JournalPhoto::STATUS_ON_CHECK;
            }
        );
    }

    /**
     * @return array|JournalPhoto[]
     */
    public function getUnpublishedPhotos()
    {
        return array_filter(
            $this->photos,
            function (JournalPhoto $photo) {
                return $photo->status != JournalPhoto::STATUS_PUBLISHED;
            }
        );
    }

    /**
     * @return ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasMany(JournalGoods::className(), ['journal_id' => 'id'])->inverseOf('journal');
    }

    /**
     * @return ActiveQuery
     */
    public function getGoodsLink()
    {
        return $this->hasMany(Goods::className(), ['id' => 'goods_id'])
            ->via('goods');
    }

    /**
     * @return ActiveQuery
     */
    public function getCheckPhotos()
    {
        return $this->hasMany(JournalCheckPhoto::className(), ['journal_id' => 'id'])->inverseOf('journal');
    }

    /**
     * @return ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id'])->via('taskUser');
    }

    /**
     * @return ActiveQuery
     */
    public function getTaskUser()
    {
        return $this->hasOne(TaskUser::className(), ['user_id' => 'user_id', 'journal_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(JournalComment::className(), ['journal_id' => 'id'])->inverseOf('journal');
    }

    /**
     * @return ActiveQuery
     */
    public function getLinkedNotifications()
    {
        return $this->hasMany(Notification::className(), ['journal_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRepairRooms()
    {
        return $this->hasMany(RoomRepair::className(), ['id' => 'room_repair_id'])
            ->viaTable('{{%journal_room_repair}}', ['journal_id' => 'id']);
    }


    /**
     * @return ActiveQuery
     */
    public function getRepairWorks()
    {
        return $this->hasMany(WorkRepair::className(), ['id' => 'work_repair_id'])
            ->viaTable('{{%journal_work_repair}}', ['journal_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getJournalTypes()
    {
        return $this->hasMany(JournalType::className(), ['id' => 'journal_type_id'])
            ->viaTable('{{%journal_journal_type}}', ['journal_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getJournalTags()
    {
        return $this->hasMany(JournalTag::className(), ['id' => 'journal_tag_id'])
            ->viaTable('{{%journal_journal_tag}}', ['journal_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getJournalOtherRoomType()
    {
        return $this->hasOne(JournalOtherRoomType::className(), ['journal_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getLikeUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
            ->viaTable('{{%journal_like_user}}', ['journal_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getFavoriteUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'userId'])
            ->viaTable('{{%user_subscription_favorite}}', ['journalId' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(UserLocation::className(), ['journal_id' => 'id']);
    }



    public function getLikeUsersCount()
    {
        return count($this->likeUsers);
    }

    public function currentUserLikeIt()
    {
        $userIds = ArrayHelper::getColumn($this->likeUsers, 'id');
        return in_array(Yii::$app->user->id, $userIds);
    }

    /**
     * Возвращает true если текущий пользователь добавил этот журнал в избранное, иначе false.
     * @return bool
     */
    public function currentUserFavoriteIt()
    {
        return UserSubscriptionFavorite::findOne(['journalId' => $this->id, 'userId' => Yii::$app->user->id]) !== null;
    }

    /**
     * Возвращает true если текущий пользователь просмотрел этот журнал, иначе false.
     * @return bool
     */
    public function currentUserViewIt()
    {
        return UserViewJournal::findOne(['journalId' => $this->id, 'userId' => Yii::$app->user->id]) !== null;
    }

    public function getGiftPoints()
    {
        if (!isset($this->_giftPoints))
            $this->_giftPoints = Gift::find()->andWhere(['journal_id' => $this->id])->sum('points');

        return $this->_giftPoints;
    }

    /**
     * Сохраняет просмотр записи журнала пользователем
     * @param $userId
     */
    public function saveViewed($userId)
    {
        $item = new UserViewJournal();
        $item->journalId = $this->id;
        $item->userId = $userId;
        $item->save();
    }

    /**
     * @return array
     */
    public static function getAllStatusJournal()
    {
        return [
            //self::SUBSCRIPTION_FEED => '',
            self::FAVORITE_POST => 'Избранные посты',
        ];
    }

    /**
     * @return array
     */
    public static function getAllStatusNamesList()
    {
        return [
            self::STATUS_DRAFT => 'Черновики',
            self::STATUS_ON_CHECK => 'На проверку',
            self::STATUS_PUBLISHED => 'Опубликованные',
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
            ['visibility', 'required'],
            ['visibility', 'number', 'integerOnly' => true],
            ['visibility', 'in', 'range' => [self::VISIBILITY_JUST_ME, self::VISIBILITY_FOR_ALL]],

            ['subject', 'filter', 'filter' => 'trim'],
            ['subject', 'required'],
            ['subject', 'string', 'min' => 3, 'max' => 100],

            [['content', 'preparation_purchase', 'store_selection', 'assessment_product', 'conclusion', 'advice', 'additional_information'],  'filter', 'filter' => 'trim'],
            [['content', 'preparation_purchase', 'store_selection', 'assessment_product', 'conclusion', 'advice', 'additional_information'],  'filter', 'filter' => function ($value) {
				return \yii\helpers\HtmlPurifier::process($value);
			}],
            ['content', 'required', 'on' => self::SCENARIO_UPDATE, 'when' => function(Journal $model) {
                return ($model->status == Journal::STATUS_ON_CHECK) && !(in_array(2, ArrayHelper::getColumn($model->journalTypes, 'id')));
            }],
            [['preparation_purchase', 'store_selection', 'assessment_product', 'conclusion'], 'required', 'on' => self::SCENARIO_UPDATE, 'when' => function(Journal $model) {
                return ($model->status == Journal::STATUS_ON_CHECK) && (in_array(2, ArrayHelper::getColumn($model->journalTypes, 'id')) && count(ArrayHelper::getColumn($model->journalTypes, 'id'))==1);
            }],
            ['content',  'string', 'min' => 3, 'on' => self::SCENARIO_UPDATE, 'when' => function(Journal $model) {
                return $model->status == Journal::STATUS_ON_CHECK;
            }],

            ['journalTypes', 'required'],

            ['repairRooms',
                'required',
                'when' => function (Journal $model) {
                    return in_array(1, ArrayHelper::getColumn($model->journalTypes, 'id')); // Работы
                },
            ],

            ['repairWorks',
                'required',
                'when' => function (Journal $model) {
                    return in_array(1, ArrayHelper::getColumn($model->journalTypes, 'id')); // Работы
                },
            ],

            ['goods',
                'required',
                'when' => function (Journal $model) {
                    return in_array(2, ArrayHelper::getColumn($model->journalTypes, 'id')); // Покупки
                },
            ],

            ['photos',
                'required',
                'when' => function (Journal $model) {
                    $journalTypes = ArrayHelper::getColumn($model->journalTypes, 'id');
                    return in_array(1, $journalTypes) || in_array(2, $journalTypes); // Работы или Покупки
                },
            ],

            ['published_id', 'integer'],
            [['view'], 'integer'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[static::SCENARIO_CREATE] = [];
        $scenarios[static::SCENARIO_UPDATE] = ['subject', 'content', 'preparation_purchase', 'store_selection', 'assessment_product', 'conclusion', 'advice', 'additional_information', 'visibility'];
        $scenarios[static::SCENARIO_CHECK] = ['visibility'];
        $scenarios[static::SCENARIO_RETURN_TO_EDIT] = ['return_reason'];
        $scenarios[static::SCENARIO_RETURN_PHOTO_TO_EDIT] = ['return_photo_reason'];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'subject' => 'Тема',
            'content' => 'Запись',
            'status' => 'Статус',
            'visibility' => 'Видимость',
            'updated_at' => 'Последнее обновление',
            'created_at' => 'Создана',
            'return_reason' => 'Причина возврата на редактирование',
            'return_photo_reason' => 'Причина возврата фотографий на редактирование',
            'journalTypes' => 'Тип поста',
            'journalTags' => 'Тэги',
            'repairWorks' => 'Работы',
            'repairRooms' => 'Помещение',
            'goods' => 'Купленные товары',
            'photos' => 'Фотографии',
            'published_id' => 'Опубликователь записи',
            'view' => 'Флаг просмотра записи',
            'preparation_purchase' => 'Подготовка к покупке',
            'store_selection' => 'Выбор магазина',
            'assessment_product' => 'Оценка выкладки товара',
            'conclusion' => 'Заключение',
            'advice' => 'Советы другим',
            'additional_information' => 'Дополнительная информация',
        );
    }

    public function beforeDelete()
    {
        // Delete relations data
        if (!$this->isNewRecord) {
            foreach ($this->photos as $photo)
                $photo->delete();

            foreach ($this->checkPhotos as $photo)
                $photo->delete();

            $this->unlinkAll('goodsLink', true);
            if ($this->taskUser) {
                $this->taskUser->journal_id = null;
				$this->taskUser->save();
            }
            // Убираем оповещения
            foreach ($this->linkedNotifications as $notification) {
                $notification->setViewedAllUsers();
            }
        }
        return parent::beforeDelete();
    }

    public static function find()
    {
        return new JournalQuery(get_called_class());
    }

    public function beforeSave($insert)
    {
        // При публикации очищаем причину отказа от публикации
        if ($this->status == self::STATUS_PUBLISHED) {
            $this->return_reason = '';
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if (($insert) or (isset($changedAttributes['status']))) {
            if ($this->status == self::STATUS_ON_CHECK) {
                // Отправка на проверку
                if ($this->task instanceof Task)
                    Yii::$app->trigger(AppEvents::EVENT_JOURNAL_BY_TASK_ON_CHECK, new Event(['sender' => $this]));
                else
                    Yii::$app->trigger(AppEvents::EVENT_JOURNAL_ON_CHECK, new Event(['sender' => $this]));
            }
            else if ($this->status == self::STATUS_PUBLISHED) {
                // Публикация
                Yii::$app->trigger(AppEvents::EVENT_JOURNAL_ON_PUBLISHED, new Event(['sender' => $this]));
            }
            else if ($this->status == self::STATUS_DRAFT) {
                if ($changedAttributes['status'] == self::STATUS_ON_CHECK) {
                    // Возврат на редактирование
                    Yii::$app->trigger(AppEvents::EVENT_JOURNAL_ON_RETURN_TO_EDIT, new Event(['sender' => $this]));
                }
            }
        }
    }

    public function init()
    {
        parent::init();
        $this->visibility = self::VISIBILITY_FOR_ALL;
    }

    public function publish()
    {
        $this->status = Journal::STATUS_PUBLISHED;
        $this->published_id = Yii::$app->user->id;
        if ($this->save(false)) {
            JournalPhoto::updateAll(['status' => JournalPhoto::STATUS_PUBLISHED], ['journal_id' => $this->id]);
            return true;
        }

        return false;
    }

    public function updateVersionToken() {
        $this->generateVersionToken();
        self::updateAttributes(['version_token']);
    }

    public function generateVersionToken()
    {
        $this->version_token = Yii::$app->security->generateRandomString();
    }

    public function getRoomsMessage()
    {
        $message = implode(', ', ArrayHelper::getColumn($this->repairRooms, 'name'));

        if (!empty($this->journalOtherRoomType->room)) {
            $message = str_replace(
                RoomRepair::OTHER_TYPE,
                RoomRepair::OTHER_TYPE . " (" . $this->journalOtherRoomType->room . ")",
                $message
            );
        }

        return $message;
    }
}
