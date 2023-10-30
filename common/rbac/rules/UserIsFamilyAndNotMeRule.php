<?php
namespace common\rbac\rules;

use common\models\User;
use yii\rbac\Item;
use yii\rbac\Rule;

class UserIsFamilyAndNotMeRule extends Rule
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

        $file = '/tmp/debug1.txt';
        $current = "";//file_get_contents($file);
        $tmp = json_encode($params);
        $current .= "\n".$tmp;
        // Пишем содержимое обратно в файл
        file_put_contents($file, $current);
        return
            ($paramUser->id != $userId) && ($paramUser->role === User::ROLE_FAMILY);
    }
}
