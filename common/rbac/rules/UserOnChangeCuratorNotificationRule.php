<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 06.06.2018
 * Time: 23:17
 */

namespace common\rbac\rules;

use common\models\User;
use yii\rbac\Item;
use yii\rbac\Rule;

class UserOnChangeCuratorNotificationRule extends Rule
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
        /**@var $paramUser User */
        $paramUser = $params['user'];
        return
            ($paramUser->id == $userId) || ($paramUser->curator_id === $userId);
    }

}