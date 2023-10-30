<?php

namespace common\models;

use common\components\ActiveRecord;
use common\components\PhotoThumbBehavior;
use common\events\AppEvents;
use common\models\interfaces\IImageModel;
use common\models\notifications\Notification;
use common\models\validators\ConversionToIntegerValidator;
use common\rbac\Rights;
use Yii;
use yii\base\ErrorException;
use yii\base\Event;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\caching\DbDependency;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $role
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property string $fio
 * @property string $phone
 * @property string $address
 * @property integer $status
 * @property string $about_user
 * @property string $about_repair
 * @property string $photo
 * @property string $family_name
 * @property integer $updated_at
 * @property integer $created_at
 * @property string $password write-only password
 * @property double $totalSpent
 * @property integer $points
 * @property integer $guide_viewed
 * @property integer $second_visit
 * @property integer $skip_all_notifications
 * @property integer $curator_id
 * @property string $authKey
 * @property integer $forumMessagesCount
 * @property integer $is_prof
 * @property integer $shop_id
 * @property integer $is_active_employee
 * @property string $register_summ
 * @property string $invite_id
 * @property int $login_count
 * @property integer $flag_buy_post
 * @property City[] $cities
 * @property Shop $user_shop
 * @property ObjectRepair[] $repairObjects
 * @property RoomRepair[] $repairRooms
 * @property WorkRepair[] $repairWorks
 * @property Notification[] $notifications
 * @property Notification[] $newNotifications
 * @property Notification[] $linkedNotifications Оповещения привязанные к этому пользователю (не для него, а с ним связанные по полю init_user_id)
 * @property Journal[] $journals
 * @property User $curator
 * @property integer $lastPublishedJournalDate
 * @property UserSubscription[] $subscriptions
 * @property UserSubscriptionFavorite[] $favorites
 * @property User[] $subscriptionToUsers
 * @mixin UserQuery
 */
class User extends ActiveRecord implements IdentityInterface, IImageModel
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_END_REPAIR = 11;
    const STATUS_NEW = 1;

    const ROLE_FAMILY = 'user';
    const ROLE_SHOP = 'shop';
    const ROLE_SHOP_MODERATOR = 'shopModerator';
    const ROLE_MARKETING = 'marketing';
    const ROLE_MARKETING_PLUS = 'marketing_plus';
    const ROLE_ALL_LM = 'all_lm';
    const ROLE_PURCHASE = 'purchase';
    const ROLE_ADMINISTRATOR = 'administrator';
    const ROLE_VIEW_JOURNAL_ONLY = 'viewJournalOnly';
    const ROLE_VIEW_JOURNAL_ONLY_ALL_CITIES = 'viewJournalOnlyAllCities';
    const ROLE_DEVELOPER = 'developer';

    const RESERVE_CURATOR = 'pristaychukj';

    const FIO_SUFFIX = 'Леруа Мерлен';

    public $set_password;
    public $set_password_confirm;

    //"Запасной" куратор,
    //если юзеру не назначен куратор, сообщения идут "запасному" куратору
    public static function getReserveCurator()
    {
        return static::find()->where(['username' => self::RESERVE_CURATOR])->one();
    }

    static public function getRoleList()
    {
        return [
            self::ROLE_FAMILY => 'Семья',
            self::ROLE_SHOP => 'Магазин-куратор',
            self::ROLE_SHOP_MODERATOR => 'Магазин-модератор',
            self::ROLE_MARKETING => 'Маркетинг',
            self::ROLE_MARKETING_PLUS => 'Маркетинг (плюс)',
            self::ROLE_ALL_LM => 'Все сотрудники ЛМ',
            self::ROLE_PURCHASE => 'Закупки',
            self::ROLE_ADMINISTRATOR => 'Администратор',
            self::ROLE_VIEW_JOURNAL_ONLY => 'Просмотр "Дневников"',
            self::ROLE_VIEW_JOURNAL_ONLY_ALL_CITIES => 'Просмотр "Дневников" всех городов',
            self::ROLE_DEVELOPER => 'Разработчик',
            self::ROLE_MARKETING_PLUS => 'Сотрудник ЦО',
        ];
    }

    static public function getUserRoleList()
    {
        $allRoles = self::getRoleList();
        unset($allRoles[self::ROLE_FAMILY]);
        return $allRoles;
    }

    /**
     * Роли, для которых нужно указывать город
     * @return array
     */
    static public function getRoleNeedSetCity()
    {
        return [
            self::ROLE_SHOP,
            self::ROLE_SHOP_MODERATOR,
            self::ROLE_VIEW_JOURNAL_ONLY
        ];
    }

    static public function getStatusList()
    {
        return [
            self::STATUS_DELETED => 'Не активен',
            self::STATUS_ACTIVE => 'Активный',
            self::STATUS_NEW => 'Новый',
            self::STATUS_END_REPAIR => 'Закончили ремонт',
        ];
    }

    public function getStatusMessage()
    {
        return self::getStatusList()[$this->status];
    }

    static public function getPosition()
    {
        return [
            self::ROLE_SHOP => ['short' => 'К', 'full' => 'КУРАТОР'],
            self::ROLE_SHOP_MODERATOR => ['short' => 'М', 'full' => 'МОДЕРАТОР'],
            self::ROLE_MARKETING => ['short' => 'М', 'full' => 'МОДЕРАТОР'],
            self::ROLE_MARKETING_PLUS => ['short' => 'М', 'full' => 'МОДЕРАТОР'],
            self::ROLE_ALL_LM => ['short' => 'М', 'full' => 'МОДЕРАТОР'],
            self::ROLE_PURCHASE => ['short' => 'М', 'full' => 'МОДЕРАТОР'],
            self::ROLE_ADMINISTRATOR => ['short' => 'А', 'full' => 'АДМИН'],
            self::ROLE_MARKETING_PLUS => ['short' => 'ЦО', 'full' => 'Сотрудник ЦО'],
        ];
    }

    public function behaviors()
    {
        return
            [
                'PhotoThumbBehavior' => [
                    'class' => PhotoThumbBehavior::className(),
                    'photoPath' => self::getPath(),
                ],
                'TimestampBehavior' => [
                    'class' => TimestampBehavior::className(),
                ],
            ];
    }

    public function getPleaAddFavorite()
    {
        return $this->hasOne(UserPleaAddFavorite::className(), ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCities()
    {
       $file = '/tmp/city.txt';
        // Открываем файл для получения существующего содержимого
        $current = "";//file_get_contents($file);
        // Добавляем нового человека в файл
        //print_r($this->_


	$tmp = $this->hasMany(City::className(), ['id' => 'city_id'])
            ->viaTable('{{%user_city}}', ['user_id' => 'id']);
	//echo "<pre>";
        //print_r($tmp);
	//echo "</pre>";
/*
        $current .= "\n".$this->hasMany(City::className(), ['id' => 'city_id'])
            ->viaTable('{{%user_city}}', ['user_id' => 'id']);
        // Пишем содержимое обратно в файл
        file_put_contents($file, $current);
*/



        return $this->hasMany(City::className(), ['id' => 'city_id'])
            ->viaTable('{{%user_city}}', ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCity()
    {
        //return $this->hasOne(City::className(), ['id' => 'city_id']);
        return $this->hasOne(City::className(), ['id' => 'city_id'])
            ->viaTable('{{%user_city}}', ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getShop()
    {
        return $this->hasOne(User::className(), ['id' => 'shop_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRepairObjects()
    {
        return $this->hasMany(ObjectRepair::className(), ['id' => 'object_repair_id'])
            ->viaTable('{{%user_object_repair}}', ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRepairRooms()
    {
        return $this->hasMany(RoomRepair::className(), ['id' => 'room_repair_id'])
            ->viaTable('{{%user_room_repair}}', ['user_id' => 'id']);
    }

    /**
     * Возвращает объект запроса на посты пользователя с привязанными товарами и магазинами.
     *
     * @return JournalQuery
     */
    public function getJournalShops()
    {
        return $this->getJournals()->withShops();
    }

    /**
     * @return ActiveQuery
     */
    public function getRepairWorks()
    {
        return $this->hasMany(WorkRepair::className(), ['id' => 'work_repair_id'])
            ->viaTable('{{%user_work_repair}}', ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCurator()
    {
        return $this->hasOne(User::className(), ['id' => 'curator_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::className(), ['id' => 'notification_id'])
            ->viaTable('{{%user_notification}}', ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getNewNotifications()
    {
        // Если стоит признак, скидывать все уведомления, то скидывем
        if ($this->skip_all_notifications) {
            Notification::setAllViewedByUserId($this->id);
        }

        return Notification::find()
            ->where(['{{%notification}}.id' => (new Query())->select('notification_id')->from('{{%user_notification}} un')->where(['viewed' => 0, 'user_id' => $this->id])]);
    }

    /**
     * @return ActiveQuery
     */
    public function getRepairLocations()
    {
        return $this->hasMany(UserLocation::className(), ['user_id' => 'id'])->where(['is_home_adress' => false]);
    }

    //используется, если не найден HomeAdress
    public function getHomeRepairLocation()
    {
        return $this->hasOne(UserLocation::className(), ['user_id' => 'id'])->where(['is_home_adress' => false]);
    }

    /**
     * @return ActiveQuery
     */
    public function getHomeAdress()
    {
        return $this->hasOne(UserLocation::className(), ['user_id' => 'id'])->where(['is_home_adress' => true]);
    }

    /**
     * @return ActiveQuery
     */
    public function getLinkedNotifications()
    {
        return $this->hasMany(Notification::className(), ['init_user_id' => 'id']);
    }

    /**
     * @return JournalQuery
     */
    public function getJournals()
    {
        return $this->hasMany(Journal::className(), ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSubscriptions()
    {
        return $this->hasMany(UserSubscription::className(), ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSubscriptionToUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'to_user_id'])
            ->via('subscriptions');
    }

    public function getSubscriptionToUserIds()
    {
        return ArrayHelper::getColumn($this->subscriptions, 'to_user_id');
    }

    /**
     * @return ActiveQuery
     */
    public function getFavorites()
    {
        return $this->hasMany(UserSubscriptionFavorite::className(), ['userId' => 'id']);
    }

    public function getFavoriteJournals()
    {
        return ArrayHelper::getColumn($this->favorites, 'journalId');
    }

    /**
     * @return integer
     */
    public function getLastPublishedJournalDate()
    {
        /** @var Journal $journal */
        $journal = $this->getJournals()
            ->select('updated_at')
            ->orderBy('updated_at DESC')
            ->one();

        return $journal instanceof Journal ? $journal->updated_at : null;
    }

	/**
	 * @return ActiveQuery
	 */
	public function getInvite()
	{
		return $this->hasOne(Invite::className(), ['invite_id' => 'id']);
	}

    /**
     * @return integer
     * @throws \Exception
     */
    public function getTotalSpent()
    {
        $user_id = $this->id;
        $db = static::getDb();

        $dependency = new DbDependency(['sql' => "SELECT MAX(updated_at) FROM {{%journal}} WHERE user_id = :user_id", 'params' => [':user_id' => $user_id]]);

        return $db->cache(
            function () use ($db, $user_id) {
                $q = 'SELECT SUM(jg.price*jg.quantity) AS total_spent
                      FROM {{%journal_goods}} jg
                          LEFT JOIN {{%journal}} j ON jg.journal_id = j.id
                      WHERE j.user_id = :user_id';

                return intval($db->createCommand($q, [':user_id' => $user_id])->queryScalar());
            },
            3600,
            $dependency
        );
    }

    private $_points = null;

    /**
     * @return integer
     * @throws \Exception
     */
    public function getPoints()
    {
        if ($this->_points == null) {
            $user_id = $this->id;
            $db = static::getDb();

            // Суммируем по записям дневника
            $dependency = new DbDependency(['sql' => "SELECT MAX(updated_at) FROM {{%journal}} WHERE user_id = :user_id", 'params' => [':user_id' => $user_id]]);

            $points = $db->cache(
                function () use ($db, $user_id) {
                    $q = 'SELECT SUM(points) AS points FROM {{%journal}} j WHERE j.user_id = :user_id';
                    return intval($db->createCommand($q, [':user_id' => $user_id])->queryScalar());
                },
                3600,
                $dependency
            );

            // Суммируем по визитам
            $dependency = new DbDependency(['sql' => "SELECT MAX(updated_at) FROM {{%visit}} WHERE user_id = :user_id", 'params' => [':user_id' => $user_id]]);

            $points += $db->cache(
                function () use ($db, $user_id) {
                    $q = 'SELECT SUM(points) AS points FROM {{%visit}} v WHERE v.user_id = :user_id';
                    return intval($db->createCommand($q, [':user_id' => $user_id])->queryScalar());
                },
                3600,
                $dependency
            );

            // Суммируем траты
            $dependency = new DbDependency(['sql' => "SELECT MAX(created_at) FROM {{%spending}} WHERE family_id = :user_id", 'params' => [':user_id' => $user_id]]);

            $points -= $db->cache(
                function () use ($db, $user_id) {
                    $q = 'SELECT SUM(points) AS points FROM {{%spending}} s WHERE s.family_id = :user_id';
                    return intval($db->createCommand($q, [':user_id' => $user_id])->queryScalar());
                },
                3600,
                $dependency
            );

            // Суммируем доп. заработки
            $dependency = new DbDependency(['sql' => "SELECT MAX(created_at) FROM {{%earnings}} WHERE family_id = :user_id", 'params' => [':user_id' => $user_id]]);

            $points += $db->cache(
                function () use ($db, $user_id) {
                    $q = 'SELECT SUM(points) AS points FROM {{%earnings}} e WHERE e.family_id = :user_id';
                    return intval($db->createCommand($q, [':user_id' => $user_id])->queryScalar());
                },
                3600,
                $dependency
            );

            // Суммируем подаренные пользователю баллы
            $dependency = new DbDependency(['sql' => "SELECT MAX(created_at) FROM {{%gift}} WHERE to_family_id = :user_id", 'params' => [':user_id' => $user_id]]);

            $points += $db->cache(
                function () use ($db, $user_id) {
                    $q = 'SELECT SUM(points) AS points FROM {{%gift}} g WHERE g.to_family_id = :user_id';
                    return intval($db->createCommand($q, [':user_id' => $user_id])->queryScalar());
                },
                3600,
                $dependency
            );

            // Вычитаем подаренные пользователем баллы
            $dependency = new DbDependency(['sql' => "SELECT MAX(created_at) FROM {{%gift}} WHERE from_family_id = :user_id", 'params' => [':user_id' => $user_id]]);

            $points -= $db->cache(
                function () use ($db, $user_id) {
                    $q = 'SELECT SUM(points) AS points FROM {{%gift}} g WHERE g.from_family_id = :user_id';
                    return intval($db->createCommand($q, [':user_id' => $user_id])->queryScalar());
                },
                3600,
                $dependency
            );

            $this->_points = $points;
        }

        return $this->_points;
    }

    public function setPoints($value)
    {
        $this->_points = $value;
    }

    public function getForumMessagesCount()
    {
        return intval(ForumMessage::find()->byUser($this->id)->count());
    }

    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'username' => 'Логин',
            'role' => 'Роль',
            'password_hash' => 'password_hash',
            'password_reset_token' => 'password_reset_token',
            'email' => 'Email',
            'auth_key' => 'auth_key',
            'fio' => 'ФИО',
            'phone' => 'Телефон',
            'address' => 'Адрес',
            'status' => 'Статус',
            'updated_at' => 'Последнее обновление',
            'created_at' => 'Вступили в проект',
            'password' => 'Пароль',
            'set_password' => 'Пароль',
            'set_password_confirm' => 'Повтор пароля',
            'family_name' => 'Как называть вашу семью?',
            'points' => 'Накоплено',
            'curator_id' => 'Куратор',
            'is_prof' => 'Профи',
            'shop_id' => 'Магазин',
            'flag_buy_post' => 'Флаг публикации поста покупки',

            'totalSpent' => 'Потратили на ремонт',
            'repairObjects' => 'Объекты ремонта',
            'repairRooms' => 'Ремонтируемые помещения',
            'repairWorks' => 'Планируемые работы',
            'cities' => 'Города',
            'plaeAddFavorite' => 'кол-во просьб добавить в избранное для юзера',

            'about_user' => $this->role == self::ROLE_FAMILY ? 'Немного о семье' : 'Немного о себе',
            'about_repair' => $this->role == self::ROLE_FAMILY ? 'Немного о ремонте, что послужило поводом и планы' : 'Немного о работе',

            'curator.fio' => 'Куратор',
            'lastPublishedJournalDate' => 'Последний пост',
            'statusMessage' => 'Статус',

            'is_active_employee' => 'Активность профиля сотрудника',
            'register_summ' => 'Контрольная сумма для подтверждения регисрации сотрудника',

			'homeAdress.adress' => 'Адрес',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_NEW],
            ['status', 'in', 'range' => [self::STATUS_NEW, self::STATUS_ACTIVE, self::STATUS_END_REPAIR, self::STATUS_DELETED]],

            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            [
                'username', 'unique',
                'targetClass' => '\common\models\User', 'message' => 'Пользователь с таким логином уже зарегистрирован.',
                'filter' => ['status' => [User::STATUS_ACTIVE, User::STATUS_END_REPAIR, User::STATUS_NEW]],
            ],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'min' => 3, 'max' => 100],
            [
                'email', 'unique',
                'when' => function ($model) {
                    return !preg_match('/^[a-zA-Z0-9_.+-]+@(?:(?:[a-zA-Z0-9-]+\\.)?[a-zA-Z]+\\.)?(leroymerlin.ru|LEROYMERLIN.RU)$/', $model->email);
                },
                'targetClass' => '\common\models\User', 'message' => 'Пользователь с такой почтой уже зарегистрирован.',
                'filter' => ['status' => [User::STATUS_ACTIVE, User::STATUS_END_REPAIR, User::STATUS_NEW]],
            ],

            ['set_password', 'required', 'on' => 'create'],
            ['set_password', 'string', 'min' => 5],

            ['set_password_confirm', 'required', 'on' => 'create'],
            ['set_password_confirm', 'string', 'min' => 5],
            ['set_password_confirm', 'compare', 'compareAttribute' => 'set_password', 'message' => 'Пароли не совпадают'],

            ['fio', 'filter', 'filter' => 'trim'],
            ['fio', 'required'],
            ['fio', 'string', 'min' => 3, 'max' => 100],

            ['phone', 'filter', 'filter' => 'trim', 'on' => 'createFamily'],
            ['phone', 'required', 'on' => 'createFamily'],
            ['phone', 'string', 'min' => 5, 'max' => 20, 'on' => 'createFamily'],

            ['address', 'filter', 'filter' => 'trim', 'on' => 'createFamily'],
            ['address', 'required', 'on' => 'createFamily'],

            //--
            ['cities',
                'required',
                'on' => ['create', 'update'],
                'when' => function ($model) {
                    return in_array($model->role, self::getRoleNeedSetCity());
                },
                'whenClient' => 'function (attribute, value) {
                    var ur = $("#user-role");
                    return ur.val() == "' . implode('" || ur.val() == "', self::getRoleNeedSetCity()) . '";
                }',
            ],

            ['role', 'default', 'value' => self::ROLE_FAMILY],
            ['role', 'required', 'on' => ['create', 'update'],],

            ['family_name', 'string', 'min' => 5, 'max' => 255],

            ['photo', 'filter', 'filter' => 'trim'],
            ['photo', 'string', 'min' => 3, 'max' => 255],

            ['curator_id', 'number', 'integerOnly' => true,],
            ['curator_id', ConversionToIntegerValidator::className()],

            ['about_user', 'required', 'on' => 'familyProfileUpdate'],

            ['about_repair', 'required', 'on' => 'familyProfileUpdate'],

            ['is_prof', 'default', 'value' => false],
            ['is_prof', 'boolean'],

            ['is_active_employee', 'default', 'value' => false],
            ['is_active_employee', 'boolean'],

            ['register_summ', 'string'],

            ['shop_id', 'number', 'integerOnly' => true,],
            ['shop_id', ConversionToIntegerValidator::className()],
            [['login_count'], 'integer'],
            ['flag_buy_post', 'default', 'value' => false],
            ['flag_buy_post', 'boolean'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['status', 'fio', 'phone', 'address', 'username', 'email', 'role', 'set_password', 'set_password_confirm', 'about_user', 'about_repair'];
        $scenarios['update'] = ['status', 'fio', 'phone', 'address', 'username', 'email', 'role', 'set_password', 'set_password_confirm', 'about_user', 'about_repair', 'login_count'];
        $scenarios['userProfileUpdate'] = ['photo', 'fio', 'email', 'phone', 'set_password', 'set_password_confirm', 'about_user', 'about_repair'];
        $scenarios['familyProfileUpdate'] = ['photo', 'fio', 'family_name', 'email', 'phone', 'set_password', 'set_password_confirm', 'about_user', 'about_repair'];
        $scenarios['resetPassword'] = [];
        $scenarios['resetNotifications'] = [];
        $scenarios['setEndRepairStatus'] = [];
        $scenarios['setIsProf'] = [];
        return $scenarios;
    }

    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    public function delete()
    {
        $this->status = self::STATUS_DELETED;
        return $this->save(false);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::find()->notDeleted()->andWhere(['id' => $id])->one();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::find()->notDeleted()->andWhere(['username' => $username])->one();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::find()->notDeleted()->andWhere(['password_reset_token' => $token])->one();
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function getRegisterSumm()
    {
        return $this->register_summ;
    }

    public function getIsActiveEmployee()
    {
        return $this->is_active_employee;
    }

    public function setIsActiveEmployee($isActiveEmployee)
    {
        $this->is_active_employee = $isActiveEmployee;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validateRegisterSumm($registerSumm)
    {
        return $this->getRegisterSumm() === $registerSumm;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new register summ
     */
    public function generateRegisteSumm()
    {
        $this->register_summ = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function afterFind()
    {
        // ANDR Подумать как можно переделать этот момент
        $authManager = \Yii::$app->authManager;
        if ($authManager->getAssignment($this->role, $this->id) === null)
            $authManager->assign($authManager->getRole($this->role), $this->id);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ((key_exists('photo', $changedAttributes)) and (strlen($this->photo) > 0))
            if (!rename(self::getTempPath() . '/' . $this->photo, self::getPath() . '/' . $this->photo)) {
                throw new ErrorException("Ошибка при сохранении фото [1]");
            }

        if (key_exists('curator_id', $changedAttributes) && isset($this->curator_id))
            Yii::$app->trigger(AppEvents::EVENT_USER_CHANGE_CURATOR, new Event(['sender' => $this]));
    }

    /**
     * @param null|integer[]|integer $cityId
     * @return array
     */
    public static function getFamiliesList($cityId = null)
    {
        $model = static::className();
        return static::getDb()->cache(
            function () use ($model, $cityId) {
                /**@var User $model */
                return ArrayHelper::map($model::find()->onlyFamiliesFioLogin($cityId)->all(), 'id', 'fio');
            },
            3600,
            static::getCacheDependency()
        );
    }

    public static function getUsersForDialog($conditions = [])
    {
        $model = static::className();
        return static::getDb()->cache(
            function () use ($model, $conditions) {
                /**@var User $model */
                return $model::find()->usersForDialog($conditions)->all();
            },
            3600,
            static::getCacheDependency()
        );
    }

    /**
     * Список кураторов
     * @return mixed
     */
    public static function getCuratorsList()
    {
        $cityId = null;
        if (\Yii::$app->user->can(Rights::SHOW_IN_MY_CITY_FAMILIES)) {
            /**@var User $user */
            $user = \Yii::$app->user->identity;
            $cityId = $user->getCities()->select('id')->column();
        }

        $model = static::className();
        return static::getDb()->cache(
            function () use ($model, $cityId) {
                /**@var User $model */
                return ArrayHelper::map($model::find()->usersCurators($cityId)->all(), 'id', 'fio');
            },
            3600,
            static::getCacheDependency()
        );
    }

    public function activate()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->save(false);

        // Вызываем событие
        Yii::$app->trigger(AppEvents::EVENT_USER_ACTIVATE, new Event(['sender' => $this]));
    }

    public function confirmation()
    {
        $this->register_summ = Yii::$app->security->generateRandomString() . '_' . time();
        $this->setIsActiveEmployee(false);
        $this->save(false);

        // Вызываем событие
        Yii::$app->trigger(AppEvents::EVENT_EMPLOYEE_REGISTRATION_CONFIRMATION, new Event(['sender' => $this]));
    }

    static public function getPath()
    {
        return Yii::getAlias('@files/family_photo');
    }

    static public function getTempPath()
    {
        return Yii::getAlias('@files/family_photo/temp');
    }

    static public function getAllowedExtensions()
    {
        return ['jpg', 'jpeg', 'png', 'gif'];
    }

    static public function getMaxSize()
    {
        return isset(Yii::$app->params['familyPhoto.maxSize']) ? Yii::$app->params['familyPhoto.MaxSize'] : 5 * 1024 * 1024;
    }

    static public function getMaxWidth()
    {
        return isset(Yii::$app->params['familyPhoto.maxWidth']) ? Yii::$app->params['familyPhoto.maxWidth'] : 300;
    }

    static public function getMaxHeight()
    {
        return isset(Yii::$app->params['familyPhoto.maxHeight']) ? Yii::$app->params['familyPhoto.maxHeight'] : 300;
    }

    static public function getTempUrlPath()
    {
        return Yii::getAlias('@web/files/family_photo/temp');
    }

    static public function getUrlPath()
    {
        return Yii::getAlias('@web/files/family_photo');
    }

    public function beforeDelete()
    {
        // Delete relations data
        if (!$this->isNewRecord) {
            // Убираем оповещения
            foreach ($this->linkedNotifications as $notification) {
                $notification->setViewedAllUsers();
            }
        }
        return parent::beforeDelete();
    }

    public function isEmployee()
    {
        return $this->role == self::ROLE_MARKETING || self::ROLE_MARKETING_PLUS || $this->role == self::ROLE_SHOP || $this->role == self::ROLE_SHOP_MODERATOR ||
            $this->role == self::ROLE_VIEW_JOURNAL_ONLY_ALL_CITIES || $this->role == self::ROLE_PURCHASE;
    }
}
