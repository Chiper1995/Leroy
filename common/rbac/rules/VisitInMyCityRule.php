<?php
namespace common\rbac\rules;

use common\models\Visit;
use common\models\User;
use yii\rbac\Item;
use yii\rbac\Rule;

class VisitInMyCityRule extends Rule
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
        /**@var $user User */
        if (isset($params['Visit'])) {
            $paramVisit = $params['Visit'];

            if ((\Yii::$app->user == null)or(($user = \Yii::$app->user->identity) == null)or($user->id != $userId))
                $user = User::findOne($userId);

            return (
                ($user->getCities()->where(['id'=>$paramVisit->creator->getCities()->select('id')->column()])->count() > 0)
                || ($user->getCities()->where(['id'=>$paramVisit->user->getCities()->select('id')->column()])->count() > 0)
            );
        }
        else {
            return true;
        }
    }
}