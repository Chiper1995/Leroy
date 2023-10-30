<?php
/**
 * Created by PhpStorm.
 * User: arshatilov
 * Date: 25.06.2018
 * Time: 15:35
 */

namespace common\rbac\rules;

use common\models\Journal;
use yii\rbac\Item;
use yii\rbac\Rule;

class EditMyJournalPhotoRule extends Rule
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
        return
            ($paramJournal->user_id == $userId)
            and ($paramJournal->status == Journal::STATUS_PUBLISHED);
    }
}