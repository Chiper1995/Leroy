<?php
namespace common\models;

use yii\db\ActiveQuery;

class DialogQuery extends ActiveQuery
{
    public function myDialogs($alias = '{{%dialog}}')
    {
        return
            $this
                ->andWhere(
                    $alias.'.id IN (SELECT du.dialog_id FROM {{%dialog_user}} du WHERE du.user_id = :user_id AND du.active = 1)',
                    [':user_id' => \Yii::$app->user->id]
                );
    }

}