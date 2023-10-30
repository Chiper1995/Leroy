<?php
namespace common\rbac\rules;

use common\models\Task;
use common\models\TaskUser;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;
use yii\rbac\Rule;

class TaskToMeRule extends Rule
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
        if (isset($params['task'])) {
            /**@var $paramTask Task */
            $paramTask = $params['task'];
            $families = array_filter($paramTask->families, function(TaskUser $taskUser) {
                return $taskUser->status == TaskUser::STATUS_ACTIVE;
            });
            $f = ArrayHelper::getColumn($families, function ($e) {
                return $e->user_id;
            });
            return (array_search($userId, $f) !== false);
        }
        else {
            return true;
        }
    }
}