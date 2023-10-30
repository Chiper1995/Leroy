<?php
namespace common\rbac\rules;

use common\models\Journal;
use common\models\Task;
use common\models\User;
use yii\rbac\Item;
use yii\rbac\Rule;

class JournalOnCheckInMyCityNotificationRule extends Rule
{
    public function init()
    {
        $this->name = static::className();
    }

    /**
     * Executes the rule.
     *
     * @param int|string $userId
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to [[ManagerInterface::checkAccess()]].
     * @return bool a value indicating whether the rule permits the auth item it is associated with.
     */
    public function execute($userId, $item, $params)
    {
        /**@var $paramJournal Journal */
        $paramJournal = $params['journal'];
        /**@var $user User */
        if ((\Yii::$app->user == null)or(($user = \Yii::$app->user->identity) == null)or($user->id != $userId))
            $user = User::findOne($userId);

        return
            (!($paramJournal->task instanceof Task))
            && ($user->getCities()->where(['id'=>$paramJournal->user->getCities()->select('id')->column()])->count() > 0);
    }
}