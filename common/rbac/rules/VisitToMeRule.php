<?php
namespace common\rbac\rules;

use common\models\Visit;
use yii\rbac\Item;
use yii\rbac\Rule;

class VisitToMeRule extends Rule
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
        if (isset($params['visit'])) {
            /**@var $paramVisit Visit */
            $paramVisit = $params['visit'];
            return $paramVisit->user_id == $userId;
        }
        else {
            return true;
        }
    }
}