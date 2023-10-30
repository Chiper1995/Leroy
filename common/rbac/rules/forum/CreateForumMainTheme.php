<?php

namespace common\rbac\rules\forum;


use common\models\ForumTheme;
use yii\rbac\Item;
use yii\rbac\Rule;

class CreateForumMainTheme extends Rule
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
        if (key_exists('parentTheme', $params)) {
            /**@var $parentTheme ForumTheme */
            $parentTheme = $params['parentTheme'];

            return $parentTheme == null;
        } else {
            return false;
        }
    }
}