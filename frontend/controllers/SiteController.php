<?php
namespace frontend\controllers;

use common\components\controllers\BaseController;
use common\models\JournalPhoto;
use common\models\User;
use Exception;
use Imagick;
use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use yii\base\InvalidParamException;
use yii\bootstrap\ActiveForm;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'except' => ['error', 'captcha', 'create-admin'],
                'rules' => [
                    [
                        'actions' => ['signup', 'login', 'request-password-reset', 'reset-password'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        //'actions' => ['*'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Создание админа
     *
     * @return mixed
     */
    public function actionCreateAdmin()
    {
        if (!YII_DEBUG) {
            return $this->goHome();
        }

        $user = User::findByUsername('admin');
        if ($user === null)
            $user = new User();
        $user->username = 'admin';
        $user->role = 'administrator';
        $user->email = 'admin@admin.ru';
        $user->setPassword('admin');
        $user->generateAuthKey();
        if ($user->save(false)) {
            if (Yii::$app->getUser()->login($user)) {
                return $this->goHome();
            }
        } else {
            return VarDumper::dumpAsString($user->errors);
        }

        return $this->goHome();
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            /** @var $user User */
            $user = Yii::$app->user->identity;

            if ($user->guide_viewed == 1 && $user->second_visit == 1 && $user->login_count == 0) {
                $user->login_count = 10;
                $user->save(false);
            }

            if ($user->login_count <= 2) {
                $user->login_count++;
                $user->save(false);
            }

            if ($user->login_count === 1) {
                $user->second_visit = 1;
            }

            if ($user->login_count === 2) {
                $user->second_visit = 0;
            }

            $user->save(false);
            
            $_SESSION['logged_in_user_id'] = '1';
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        unset($_SESSION['logged_in_user_id']);
        return $this->goHome();
    }


    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                return $this->goHome();
            }
            else {
                Yii::$app->session->setFlash('error', 'Возникла ошибка при попытке восстановить пароль, пожалуйста повторите попытку позже');
            }
            $model = new PasswordResetRequestForm();
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Новый пароль успешно сохранен');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionOptimizePhotos()
    {
        $_date = null;

        $directory = JournalPhoto::getPath().'/';
        if ($d = @opendir($directory)) {
            while (($file = readdir($d)) !== false) { //считываем дерикторию
                if (($file != ".") && ($file != "..") && (!is_dir($directory . $file))) {
                    // проверяем давность создания файла
                    $ftime = filemtime($directory . $file); // смотрим время создания


                    if ((date('d.m.Y', $ftime) != '17.05.2016') && (date('d.m.Y', $ftime) != '16.05.2016')) {

                        try {
                            $thumb = new Imagick($directory . $file);
                            if ($thumb->getImageFormat() == 'JPEG') {
                                $thumb->setImageFormat("jpg");
                                $thumb->setImageCompression(imagick::COMPRESSION_JPEG);
                                $thumb->setImageCompressionQuality(70);
                                $thumb->gaussianBlurImage(0.05, 0.5);
                                $thumb->writeImage();
                                $thumb->destroy();

                                echo $directory . $file . '<br/>';
                            }
                        } catch (Exception $e) {
                            echo 'ERROR: '.$directory . $file.': ',  $e->getMessage(), "\n".'<br/>';
                        }
                    }

                }
            }
            closedir($d);
        }
    }

    public function actionTestEmailSending($email)
	{
		Yii::$app->mailer
			->compose()
			->setTo($email)
			->setSubject('Тестовое сообщение')
			->setTextBody('Тестовое сообщение')
			->send();
	}
}
