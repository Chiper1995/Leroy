<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 12.06.2018
 * Time: 17:22
 */

namespace common\rbac\rules;

use common\models\Journal;
use common\models\Task;
use yii\rbac\Item;
use yii\rbac\Rule;

class JournalByTaskOnCheckByUserCuratorNotificationRule extends Rule
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
        return ($paramJournal->task instanceof Task) and ($paramJournal->user->curator_id == $userId);
    }
}