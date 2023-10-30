<?php
namespace common\components;


/**
 * Class User
 * @package common\components
 *
 * @property \common\models\User $identity
 */
class User extends \yii\web\User
{
    public function getRole()
    {
        $identity = $this->getIdentity();

        return $identity !== null ? $identity->role : null;
    }
}