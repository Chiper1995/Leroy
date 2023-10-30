<?php
namespace common\rbac\rules\dialog;

use common\models\DialogMessage;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;
use yii\rbac\Rule;

class DialogMessageToMeRule extends Rule
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
        if (isset($params['dialogMessage'])) {
            /**@var $paramDialogMessage DialogMessage */
            $paramDialogMessage = $params['dialogMessage'];

            if ($userId == $paramDialogMessage->user_id)
                return false;

            $f = ArrayHelper::getColumn($paramDialogMessage->dialog->allUsers, function ($e) {
                return $e->id;
            });
            return (array_search($userId, $f) !== false);
        }
        else {
            return true;
        }
    }
}