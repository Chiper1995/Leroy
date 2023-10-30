<?php

namespace console\controllers;

use common\models\User;
use yii\console\Controller;

class FamilyController extends Controller
{
    public function actionMergeFamily()
    {
        /** @var User[] $users */
        // SELECT * from bs_user WHERE role = 'user' AND family_name IS NULL OR family_name = '';
        $users = User::find()
            ->where(['role' => User:: ROLE_FAMILY])
            ->andWhere(['is', 'family_name', new \yii\db\Expression('null')])
            ->orWhere(['=', 'family_name', ''])
            ->all();
        foreach($users as $user) {
            if ($user->fio) {
                $user->family_name = $user->fio;
            }
            $user->save();
            echo date('r') . ': ' . $user->id . ' merged with FIO.' . "\n";
        }
    }
}