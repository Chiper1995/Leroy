<?php
namespace common\rbac\rules;

use common\models\JournalComment;
use common\models\User;
use yii\rbac\Item;
use yii\rbac\Rule;

class AddInMyCityCommentNotificationRule extends Rule
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
        /**@var $paramJournalComment JournalComment */
        $paramJournalComment = $params['journalComment'];
        /**@var $user User*/
        if ((\Yii::$app->user == null)or(($user = \Yii::$app->user->identity) == null)or($user->id != $userId))
            $user = User::findOne($userId);

        return
            ($paramJournalComment->user_id != $userId) // не свой комментарий
            && ($user->getCities()->where(['id'=>$paramJournalComment->journal->user->getCities()->select('id')->column()])->count() > 0)
            ;
    }
}