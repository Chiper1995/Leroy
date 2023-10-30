<?php

namespace common\events;

use common\components\JournalRewardsMaker;
use common\components\UserEmailSender;
use common\components\VisitRewardsMaker;
use common\models\Invite;
use common\models\Journal;
use common\models\notifications\Notification;
use common\models\User;
use common\models\Visit;
use yii\base\Event;
use yii\base\Exception;

/**
 * Class AppEventsHandler
 * @package common\events
 *
 */
class AppEventsHandler
{
    /**
     * @param $event \yii\base\Event
     * @param $notificationClass
     * @return bool
     * @throws Exception
     */
    static function onAppEventNotification($event, $notificationClass)
    {
        /**@var $notification Notification */
        try {
            $notification = $notificationClass::createFromEvent($event);
        } catch (Exception $e) {
            throw new Exception("Ошибка при создании уведомления о событии \"{$notificationClass}\": {$e->getMessage()}");
        }

        if (!$notification->save()) {
            throw new Exception("Ошибка при сохранении уведомления о событии \"{$notificationClass}\": " . print_r($notification->firstErrors, true));
        }

        \Yii::info("Уведомление о событии \"{$notificationClass}\" сохранено");
    }

    /**
     * Начисляем баллы за записи журналов
     *
     * @param $event
     * @throws Exception
     */
    static function onAppEventJournalMakeRewards($event)
    {
        if (!($event->sender instanceof Journal))
            throw new Exception("Параметр sender должен содержать экземпляр типа \"" . Journal::className() . "\"");

        // Начисляем баллы
        (new JournalRewardsMaker($event->sender))->make();
    }

    /**
     * Начисляем баллы за визиты
     *
     * @param Event $event
     * @throws Exception
     */
    static function onAppEventVisitMakeRewards(Event $event)
    {
        if (!($event->sender instanceof Visit))
            throw new Exception("Параметр sender должен содержать экземпляр типа \"" . Visit::className() . "\"");

        // Начисляем баллы
        if ($event->name == AppEvents::EVENT_VISIT_AGREED_FAMILY)
            (new VisitRewardsMaker($event->sender))->makeWithoutTimeChanging();
        else if ($event->name == AppEvents::EVENT_VISIT_AGREED)
            (new VisitRewardsMaker($event->sender))->makeWithTimeChanging();
    }

    /**
     * Отправляем письмо об активации на почту
     *
     * @param $event
     * @throws Exception
     */
    static function onAppEventUserActivate($event)
    {
        //if (!($event->sender instanceof User))
        //    throw new Exception("Параметр sender должен содержать экземпляр типа \"" . User::className() . "\"");

        // Отправляем письмо
        //UserEmailSender::onUserActivateEmailSend($event->sender);
    }

    /**
     * Отправляем письмо с ссылкой для сброса пароля на почту
     *
     * @param $event
     * @throws Exception
     */
    static function onAppEventUserPasswordReset($event)
    {
        if (!($event->sender instanceof User))
            throw new Exception("Параметр sender должен содержать экземпляр типа \"" . User::className() . "\"");

        // Отправляем письмо
        UserEmailSender::onUserUserPasswordResetEmailSend($event->sender);
    }

    static function onAppEventJournalReturnToEdit($event)
    {
        if (!($event->sender instanceof Journal))
            throw new Exception("Параметр sender должен содержать экземпляр типа \"" . Journal::className() . "\"");

        // Отправляем письмо
        UserEmailSender::onJournalReturnToEdit($event->sender);
    }

    static function onAppEventLastVisitsMail($event)
    {
        $options = isset($event->mailOptions) ? $event->mailOptions : [];
        $title = isset($event->mailTitle) ? $event->mailTitle : null;

        if (!empty($event->mailRecipient)) {
            UserEmailSender::onLastVisitsMail(
                $event->mailView,
                $event->mailRecipient,
                $title,
                $options
            );
        }
    }

    /**
     * Отправляем письмо с подтверждением регистрации сотрудника
     *
     * @param $event
     * @throws Exception
     */
    static function onAppEventEmployeeRegistrationConfirmation($event)
    {
        if (!($event->sender instanceof User))
            throw new Exception("Параметр sender должен содержать экземпляр типа \"" . User::className() . "\"");
        // Отправляем письмо
        UserEmailSender::onEmployeeRegistrationEmailSend($event->sender);
    }

	/**
	 * Отправляем письмо с ссылкой на регистрацию
	 *
	 * @param $event
	 * @throws Exception
	 * @throws \Exception
	 */
	static function onAppEventRegistrationLinkSend($event)
	{
		if (!($event->sender instanceof Invite)) {
			throw new Exception("Параметр sender должен содержать экземпляр типа \"" . Invite::className() . "\"");
		}

		// Отправляем письмо
		UserEmailSender::onRegistrationLinkSend($event->sender);
	}
}
