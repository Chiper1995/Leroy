<?php
namespace common\rbac\rules;

use common\models\Task;
use common\models\User;
use yii\db\Query;
use yii\rbac\Item;
use yii\rbac\Rule;

class TaskInMyCityRule extends Rule
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
        /**@var $paramTask Task */
        /**@var $user User */
        if (isset($params['task'])) {
            $paramTask = $params['task'];

            if ((\Yii::$app->user == null)or(($user = \Yii::$app->user->identity) == null)or($user->id != $userId))
                $user = User::findOne($userId);

            return (
                ($user->getCities()->where(['id'=>$paramTask->creator->getCities()->select('id')->column()])->count() > 0)
                || (
                    $paramTask->getFamilies()
                        ->where(['user_id'=>(new Query())->select('user_id')->from('{{%user_city}} uc')->where(['uc.city_id' => $user->getCities()->select('id')->column()])])
                        ->count() > 0
                )
            );
        }
        else {
            return true;
        }
    }
}