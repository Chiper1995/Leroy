<?php
namespace common\models\notifications;

use common\components\ActiveRecord;
use common\models\User;
use ReflectionClass;
use yii\base\Exception;
use yii\base\Model;

/**
 * Class Notification
 *
 * @property integer $id
 * @property string $type
 */
class Notification extends ActiveRecord
{
    static protected $RIGHT = '';

    static protected $TYPE = null;

    public $count;

    public static function tableName()
    {
        return '{{%notification}}';
    }

    /**
     * @param $event \yii\base\Event
     * @return Notification
     * @throws Exception
     */
    static protected function createFromEvent($event)
    {
        throw new Exception('Нельзя создавать уведомления класса \"' . self::className() . "\"");
    }

    protected function getParamsForCheckPermission()
    {
        return [];
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            /**@var $users User[] */
            $users = User::find()->notDeleted()->all();
            foreach ($users as $user) {
                if (\Yii::$app->getAuthManager()->checkAccess($user->id, static::$RIGHT, $this->getParamsForCheckPermission())) {
                    $user->link('notifications', $this);
                }
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public static function instantiate($row)
    {
        return (new ReflectionClass('common\\models\\notifications\\' . $row['type']))->newInstance();
    }

    public static function find()
    {
        return new NotificationQuery(get_called_class(), ['type' => static::$TYPE]);
    }

    public function beforeSave($insert)
    {
        $this->type = static::$TYPE;
        return parent::beforeSave($insert);
    }

    public function beforeDelete()
    {
        $this->getDb()->createCommand()
            ->delete('{{%user_notification}}', ['notification_id' => $this->id])
            ->execute();

        return parent::beforeDelete();
    }

    public function init()
    {
        $this->type = static::$TYPE;
        parent::init();
    }

    public function transactions()
    {
        return [
            Model::SCENARIO_DEFAULT => ActiveRecord::OP_INSERT
        ];
    }

    /**
     * @param int $userId
     * @return bool
     */
    public function setViewed($userId)
    {
        return
            $this->getDb()->createCommand()
                ->update('{{%user_notification}}', ['viewed' => true], ['user_id' => $userId, 'notification_id' => $this->id, 'viewed' => false])
                ->execute();
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function setViewedAllUsers()
    {
        return
            $this->getDb()->createCommand()
                ->update('{{%user_notification}}', ['viewed' => true], ['notification_id' => $this->id, 'viewed' => false])
                ->execute() > 0;
    }

    /**
     * @param int $userId
     * @param bool $resetDialogsNotifications
     * @return bool
     * @throws \yii\db\Exception
     */
    public static function setAllViewedByUserId($userId, $resetDialogsNotifications = false)
    {
        $db = \Yii::$app->getDb();

        $sql =
            'UPDATE {{%user_notification}} un ' .
            'JOIN {{%notification}} n ON n.id = un.notification_id ' .
            'SET ' .
            'viewed = 1 ' .
            'WHERE ' .
            'user_id = :user_id ' .
            'AND viewed = 0 ';

       if (!$resetDialogsNotifications) {
            $sql .= ' AND n.type NOT IN (' . $db->quoteValue(dialog\DialogNewMessageNotification::$TYPE) . ')';
        }

        return $db->createCommand($sql, [':user_id' => $userId])->execute() > 0;
    }

    /**
     * @param int|array $notificationId
     * @return bool
     */
    public static function setAllViewedByNotificationId($notificationId)
    {
        return
            \Yii::$app->getDb()->createCommand()
                ->update('{{%user_notification}}', ['viewed' => true], ['notification_id' => $notificationId, 'viewed' => false])
                ->execute() > 0;
    }
}
