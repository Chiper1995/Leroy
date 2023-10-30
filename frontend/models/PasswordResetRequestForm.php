<?php
namespace frontend\models;

use common\events\AppEvents;
use common\models\User;
use Yii;
use yii\base\Event;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => [User::STATUS_ACTIVE, User::STATUS_END_REPAIR, User::STATUS_NEW]],
                'message' => 'Пользователя с таким email адресом не найдено'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => [User::STATUS_ACTIVE, User::STATUS_END_REPAIR, User::STATUS_NEW],
            'email' => $this->email,
        ]);

        if ($user) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }

            if ($user->save()) {
                // Вызываем событие
                Yii::$app->trigger(AppEvents::EVENT_USER_PASSWORD_RESET, new Event(['sender' => $user]));
                return true;
            }
        }

        return false;
    }
}
