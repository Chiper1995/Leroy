<?php

namespace frontend\widgets\NavBarUserCard;

use Yii;
use yii\base\Widget;
use common\models\User;

class NavBarUserCard extends Widget
{
    public function run()
    {
        $user = Yii::$app->user->identity;
        if (strpos($user->fio, User::FIO_SUFFIX) === false && \Yii::$app->session->get('add_suffix') == 'Y')
            $user->fio .= ' ' . User::FIO_SUFFIX;
        return $this->render('index', ['user' => Yii::$app->user->identity]);
    }
}