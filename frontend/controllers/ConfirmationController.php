<?php

namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use yii\web\Response;
use common\models\User;

/**
 * Class ConfirmationController
 */
class ConfirmationController extends Controller
{

    public function actionIndex()
    {
        if (Yii::$app->request->get('employeeActivate') && $username = Yii::$app->request->get('username')) {
            $user = User::findByUsername($username);
            if ($user && $user->validateRegisterSumm(Yii::$app->request->get('employeeActivate'))) {
                $user->setIsActiveEmployee(true);
                $user->save();
                $user->activate();
                return $this->render('index', [
                    'username' => $username,
                ]);
            }
        }
        return $this->render('error');
    }

}
