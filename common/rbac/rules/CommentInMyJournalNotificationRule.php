<?php
namespace common\rbac\rules;

use common\models\JournalComment;
use yii\rbac\Item;
use yii\rbac\Rule;

class CommentInMyJournalNotificationRule extends Rule
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
        return
            ($paramJournalComment->user_id != $userId) // не свой комментарий
            and ($paramJournalComment->journal->user_id == $userId);
    }
}