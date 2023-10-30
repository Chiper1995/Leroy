<?php
namespace common\models;

use common\components\ActiveRecord;
use common\rbac\Rights;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * Class Dialog
 * @package common\models
 *
 * @property integer $id
 * @property string $subject
 * @property integer $author_id
 * @property integer $updated_at
 * @property integer $created_at
 * @property User $author
 * @property User[] $users
 * @property User[] $allUsers
 * @property DialogMessage[] $messages
 * @property DialogMessage $lastMessage
 *
 * @mixin DialogQuery
 */
class Dialog extends ActiveRecord
{
    public function behaviors()
    {
        return [
            'TimestampBehavior' => [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
            ->viaTable('{{%dialog_user}}', ['dialog_id' => 'id'], function ($q) {
                return $q->andWhere(['active' => 1])->andWhere(['read_only' => 0]);
            });
    }

    public function getAllUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
            ->viaTable('{{%dialog_user}}', ['dialog_id' => 'id'], function ($q) {
                return $q->andWhere(['active' => 1]);
            });
    }

    /**
     * @return ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(DialogMessage::className(), ['dialog_id' => 'id'])->inverseOf('dialog');
    }

    /**
     * @return ActiveQuery
     */
    public function getLastMessage()
    {
        return $this->hasOne(DialogMessage::className(), ['dialog_id' => 'id'])->orderBy(['id'=>SORT_DESC])->limit(1);
    }

    private $_newMessagesCount = null;

    /**
     * @param int $userId
     * @return int
     */
    public function getNewMessagesCount($userId)
    {
        if ($this->_newMessagesCount == null) {
            $db = static::getDb();
            $q = 'SELECT COUNT(*) FROM {{%user_notification}} 
              WHERE 
                viewed = 0 
                AND user_id = :user_id 
                AND notification_id IN (SELECT id FROM {{%notification}} n WHERE n.dialog_message_id IN (SELECT id FROM {{%dialog_message}} dm WHERE dm.dialog_id = :dialog_id))';

            $this->_newMessagesCount = intval($db->createCommand($q, [':user_id' => $userId, ':dialog_id' => $this->id])->queryScalar());
        }

        return $this->_newMessagesCount;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['subject', 'filter', 'filter' => 'trim'],
            ['subject', 'required'],
            ['subject', 'string', 'max' => 255],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['subject'];
        $scenarios['update'] = ['subject'];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return array(
            'subject' => 'Тема'
        );
    }

    public static function find()
    {
        return new DialogQuery(get_called_class());
    }

    /**
     * Список семей
     * @return mixed
     */
    public static function getUsersForDialog()
    {
        /**@var User $user*/
        $u = Yii::$app->user;
        $user = $u->identity;
        $usersInCitiesQuery = (new Query())->select('user_id')->from('{{%user_city}} uc')->where(['uc.city_id' => $user->getCities()->select('id')->column()]);
        $conditions = [];

        // Может общаться с магазинами
        if ($u->can(Rights::CREATE_DIALOGS_WITH_SHOP)) {
            // Только из своего города
            if ($u->can(Rights::CREATE_DIALOGS_WITH_SHOP_IN_MY_CITY)) {
                $conditions[] = [['role' => User::ROLE_SHOP, 'id' => $usersInCitiesQuery]];
            }
            else {
                $conditions[] = [['role' => User::ROLE_SHOP]];
            }
        }

        // Может общаться с семьями
        if ($u->can(Rights::CREATE_DIALOGS_WITH_FAMILY)) {
            // Только из своего города
            if ($u->can(Rights::CREATE_DIALOGS_WITH_FAMILY_IN_MY_CITY)) {
                $conditions[] = [['role' => User::ROLE_FAMILY, 'id' => $usersInCitiesQuery]];
            }
            else {
                $conditions[] = [['role' => User::ROLE_FAMILY]];
            }
        }

        // Может общаться с администраторами
        if ($u->can(Rights::CREATE_DIALOGS_WITH_ADMINISTRATOR)) {
            $conditions[] = [['role' => User::ROLE_ADMINISTRATOR]];
        }

        // Если есть только CREATE_DIALOGS, то может писать всем
        if (count($conditions)==0) {
            if ($u->can(Rights::CREATE_DIALOGS)) {
                $conditions = [];
            }
            else {
                // На всякий случай блокируем
                $conditions[] = ['1=2'];
            }
        }

        return User::getUsersForDialog($conditions);
    }

}