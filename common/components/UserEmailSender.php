<?php

namespace common\components;

//use common\models\User;
use common\models\Help;
use common\models\Invite;
use yii\bootstrap\Html;
use yii\log\Logger;
use Exception;
use Yii;

class UserEmailSender
{
    /**
     * @param \common\models\User $user
     */
    static function onUserActivateEmailSend($user)
    {
        if (empty($user->email))
            return;

        try {
            /**@var $help Help*/
            $helpContent = '';

            if (($help = Help::findOne(['default'=>1])) !== null)
                $helpContent = $help->content;

            Yii::$app->mailer
                ->compose('userActivate', [
                    'help' => $helpContent,
                    'user' => $user
                ])
                ->setTo($user->email)
                ->setSubject('Подтверждение регистрации на портале «Семьи Леруа Мерлен»')
                ->send();
        }
        catch (Exception $e) {
            Yii::$app->session->setFlash('error', Html::icon('alert') . ' Возникла ошибка при отправки сообщения на email пользователя, проверьте адрес');
            Yii::getLogger()->log('onUserActivateEmailSend: Ошибка при отправке письма на адрес "'.$user->email.'" ('.$e->getMessage().')', Logger::LEVEL_ERROR);
        }
    }

    /**
     * @param \common\models\User $user
     */
    static function onUserUserPasswordResetEmailSend($user)
    {
        if (empty($user->email))
            return;

        try {
            Yii::$app->mailer
                ->compose('passwordResetToken', [
                    'user'=>$user
                ])
                ->setTo($user->email)
                ->setSubject('Восстановление пароля на портале «Семьи Леруа Мерлен»')
                ->send();

            Yii::$app->session->setFlash('success', 'Инструкции по восстановлению пароля высланы вам на почту');
        }
        catch (Exception $e) {
            Yii::$app->session->setFlash('error', Html::icon('alert') . ' Возникла ошибка при отправки сообщения на ящик '.$user->email.'');
            Yii::getLogger()->log('onUserUserPasswordResetEmailSend: Ошибка при отправке письма на адрес "'.$user->email.'" ('.$e->getMessage().')', Logger::LEVEL_ERROR);
        }
    }

    public static function onJournalReturnToEdit($journal)
    {
        if (empty($journal->user->email))
            return;

        try {
            Yii::$app->mailer
                ->compose('journalReturned', [
                    'journal' => $journal,
                ])
                ->setTo($journal->user->email)
                ->setSubject('Ваш пост был отправлен на доработку')
                ->send();
        }
        catch (Exception $e) {
            Yii::$app->session->setFlash('error', Html::icon('alert') . ' Возникла ошибка при отправки сообщения на ящик '.$journal->user->email.'');
            Yii::getLogger()->log('onJournalReturnToEdit: Ошибка при отправке письма на адрес "'.$journal->user->email.'" ('.$e->getMessage().')', Logger::LEVEL_ERROR);
        }
    }


    public static function onLastVisitsMail($view, $recipient, $title, $options = [])
    {
        try {
            Yii::$app->mailer
                ->compose($view, [
                    'options' => $options,
                ])
                ->setTo($recipient)
                ->setSubject($title)
                ->send();
        }
        catch (Exception $e) {
            if (!empty(Yii::$app->session)) {
                Yii::$app->session->setFlash('error',
                    Html::icon('alert') . ' Возникла ошибка при отправки сообщения на ящик ' . $recipient . '');
            }
            Yii::getLogger()->log('onLastVisitsMail: Ошибка при отправке письма на адрес "'.$recipient.'" ('.$e->getMessage().')', Logger::LEVEL_ERROR);
        }
    }

    public static function onCuratorDeleteUser($user, $curatorID)
    {
//        if (!($user instanceof User))
//            throw new Exception("Параметр sender должен содержать экземпляр типа \"" . User::className() . "\"");

        //если куратор удалил своего юзера - сообщение не отправлять
        if (isset($user->curator->id) && $user->curator->id == $curatorID)
            return;

        //если удаляемый пользователь куратор или другая руководящая роль
        if ($user->role != 'user')
            return;

        if (empty($user->curator->email))
            return;

        try {
            Yii::$app->mailer
                ->compose('userDelete', [
                    'user' => $user,
                ])
                ->setTo($user->curator->email)
                ->setSubject('Семья была удалена')
                ->send();
        }
        catch (Exception $e) {
            Yii::$app->session->setFlash('error', Html::icon('alert') . ' Возникла ошибка при отправки сообщения на ящик '.$user->curator->email.'');
            Yii::getLogger()->log('onCuratorDeleteUser: Ошибка при отправке письма на адрес "'.$user->curator->email.'" ('.$e->getMessage().')', Logger::LEVEL_ERROR);
        }
    }

    public static function onCuratorUserChangeCurator($user)
    {
$file = '/tmp/user.txt';
        // Открываем файл для получения существующего содержимого
        $current = "";//file_get_contents($file);
        // Добавляем нового человека в файл
        //print_r($this->_
        //$current .= "MYTEST\n".$user;
        $user->toArray();
        //$user->toStrng();
        $current = get_class($user);
        // Пишем содержимое обратно в файл
        file_put_contents($file, $current);


        //if (!($user instanceof User))
        //    throw new Exception("Параметр sender должен содержать экземпляр типа \"" . User::className() . "\"");

        //если удаляемый пользователь куратор или другая руководящая роль
        if ($user->role != 'user')
            return;

        if (empty($user->curator->email))
            return;

        try {
            Yii::$app->mailer
                ->compose('userChangeCurator', [
                    'user' => $user,
                ])
                ->setTo($user->curator->email)
                ->setSubject('Новая семья на платформе')
                ->send();
        }
        catch (Exception $e) {
            Yii::$app->session->setFlash('error', Html::icon('alert') . ' Возникла ошибка при отправки сообщения на ящик '.$user->curator->email.'');
            Yii::getLogger()->log('onCuratorUserChangeCurator: Ошибка при отправке письма на адрес "'.$user->curator->email.'" ('.$e->getMessage().')', Logger::LEVEL_ERROR);
        }
    }

    /**
     * @param \common\models\User $user
     */
    public static function onEmployeeRegistrationEmailSend($user)
    {
        if (empty($user->email))
            return;

        try {
            Yii::$app->mailer
                ->compose('employeeActivate', [
                    'user' => $user
                ])
                ->setFrom(Yii::$app->params['mailer.transport.username'])
                ->setTo($user->email)
                ->setSubject('Подтверждение регистрации сотрудника на портале «Семьи Леруа Мерлен»')
                ->send();

            Yii::$app->session->setFlash('success', 'Инструкция со ссылкой для подтверждения регистрации выслана вам на почту');

        }
        catch (Exception $e) {
            Yii::$app->session->setFlash('error', Html::icon('alert') . ' Возникла ошибка при отправке сообщения на email пользователя, проверьте адрес');
            Yii::getLogger()->log('onEmployeeRegistrationEmailSend: Ошибка при отправке письма на адрес "'.$user->email.'" ('.$e->getMessage().')', Logger::LEVEL_ERROR);
        }
    }

	/**
	 * @param $invite
	 * @throws Exception
	 */
	public static function onRegistrationLinkSend($invite)
	{
		if (!($invite instanceof Invite)) {
			throw new Exception("Параметр sender должен содержать экземпляр типа \"" . Invite::className() . "\"");
		}

		if ($invite->status === Invite::STATUS_REGISTERED) {
			return;
		}

		if (empty($invite->email)) {
			return;
		}

		try {
		Yii::$app->mailer
				->compose('registrationLink', ['invite' => $invite,])
				//->setFrom(array('families@leroymerlin.ru' => 'John Doe'))
				->setTo($invite->email)
				->setSubject('Встречайте новую семью Леруа Мерлен!')
				->send();
		}
		catch (Exception $e) {
			Yii::$app->session->setFlash('error', Html::icon('alert') . ' Возникла ошибка при отправки сообщения на ящик ' . $invite->email . '');
			Yii::getLogger()->log('onRegistrationLinkSend: Ошибка при отправке письма на адрес "' . $invite->email . '" (' . $e->getMessage() . ')', Logger::LEVEL_ERROR);
		}
	}
}
