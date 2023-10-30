<?php
namespace common\rbac\rules;

use common\models\User;
use yii\rbac\Item;
use yii\rbac\Rule;

class DeleteMyUserRule extends Rule
{
    public function init()
    {
        $this->name = static::className();
    }


    public function execute($userId, $item, $params)
    {

        $paramUser = $params['user'];

        return $userId == $paramUser->curator_id;
    }
}
