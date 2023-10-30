<?php
namespace common\rbac\rules;

use common\models\Visit;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;
use yii\rbac\Rule;

class EditOwnVisitRule extends Rule
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
        /**@var $paramVisit Visit */
        $paramVisit = $params['visit'];
        return ($paramVisit->creator_id == $userId)and($paramVisit->status != Visit::STATUS_AGREED);
    }
}