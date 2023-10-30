<?php
/**
 * События приложения
 */

use common\events\AppEvents;
use common\events\AppEventsHandler;
use common\components\UserEmailSender;

return [
    'on '. AppEvents::EVENT_NEW_USER_REGISTER => function ($event) {
        AppEventsHandler::onAppEventNotification($event, common\models\notifications\NewUserRegisterNotification::className());
    },

    'on '. AppEvents::EVENT_USER_ACTIVATE => function ($event) {
        AppEventsHandler::onAppEventUserActivate($event);
    },

    'on '. AppEvents::EVENT_USER_DELETE => function ($event) {
        AppEventsHandler::onAppEventNotification($event, common\models\notifications\DeleteUserNotification::className());
        UserEmailSender::onCuratorDeleteUser($event->sender, $event->curatorID);
    },

    'on '. AppEvents::EVENT_USER_CHANGE_CURATOR => function ($event) {
        AppEventsHandler::onAppEventNotification($event, common\models\notifications\UserOnChangeCuratorNotification::className());
        UserEmailSender::onCuratorUserChangeCurator($event->sender);
    },

    'on '. AppEvents::EVENT_JOURNAL_ON_CHECK => function ($event) {
        AppEventsHandler::onAppEventNotification($event, common\models\notifications\JournalOnCheckNotification::className());
    },

    'on '. AppEvents::EVENT_JOURNAL_BY_TASK_ON_CHECK => function ($event) {
        AppEventsHandler::onAppEventNotification($event, common\models\notifications\JournalByTaskOnCheckNotification::className());
    },

    'on '. AppEvents::EVENT_JOURNAL_PHOTO_ON_CHECK => function ($event) {
        AppEventsHandler::onAppEventNotification($event, common\models\notifications\JournalPhotoOnCheckNotification::className());
    },

    'on '. AppEvents::EVENT_JOURNAL_ON_PUBLISHED => function ($event) {
        AppEventsHandler::onAppEventJournalMakeRewards($event);
        AppEventsHandler::onAppEventNotification($event, common\models\notifications\JournalOnPublishedNotification::className());
    },

	'on '. AppEvents::EVENT_JOURNAL_ON_TYPE_CHANGED => function ($event) {
        AppEventsHandler::onAppEventJournalMakeRewards($event);
    },

    'on '. AppEvents::EVENT_JOURNAL_ON_RETURN_TO_EDIT => function ($event) {
        AppEventsHandler::onAppEventNotification($event, common\models\notifications\JournalOnReturnToEditNotification::className());
        AppEventsHandler::onAppEventJournalReturnToEdit($event);
    },

    'on '. AppEvents::EVENT_JOURNAL_PHOTO_ON_PUBLISHED => function ($event) {
        AppEventsHandler::onAppEventNotification($event, common\models\notifications\JournalPhotoOnPublishedNotification::className());
    },

    'on '. AppEvents::EVENT_JOURNAL_PHOTO_ON_RETURN_TO_EDIT => function ($event) {
        AppEventsHandler::onAppEventNotification($event, common\models\notifications\JournalPhotoOnReturnToEditNotification::className());
    },

    'on '. AppEvents::EVENT_TASK_ADDED => function ($event) {
        AppEventsHandler::onAppEventNotification($event, common\models\notifications\TaskOnAddNotification::className());
    },

    'on '. AppEvents::EVENT_VISIT_ON_AGREEMENT => function ($event) {
        AppEventsHandler::onAppEventNotification($event, common\models\notifications\VisitOnAgreementNotification::className());
    },

    'on '. AppEvents::EVENT_VISIT_AGREED_FAMILY => function ($event) {
        AppEventsHandler::onAppEventVisitMakeRewards($event);
        AppEventsHandler::onAppEventNotification($event, common\models\notifications\VisitAgreedFamilyNotification::className());
    },

    'on '. AppEvents::EVENT_VISIT_TIME_EDITED_FAMILY => function ($event) {
        AppEventsHandler::onAppEventNotification($event, common\models\notifications\VisitTimeEditedFamilyNotification::className());
    },

    'on '. AppEvents::EVENT_VISIT_CANCELED_FAMILY => function ($event) {
        AppEventsHandler::onAppEventNotification($event, common\models\notifications\VisitCanceledFamilyNotification::className());
    },

    'on '. AppEvents::EVENT_VISIT_AGREED => function ($event) {
        AppEventsHandler::onAppEventVisitMakeRewards($event);
        AppEventsHandler::onAppEventNotification($event, common\models\notifications\VisitAgreedNotification::className());
    },

    'on '. AppEvents::EVENT_VISIT_CANCELED => function ($event) {
        AppEventsHandler::onAppEventNotification($event, common\models\notifications\VisitCanceledNotification::className());
    },

    'on '. AppEvents::EVENT_USER_PASSWORD_RESET => function ($event) {
        AppEventsHandler::onAppEventUserPasswordReset($event);
    },

    'on '. AppEvents::EVENT_JOURNAL_ADD_COMMENT => function ($event) {
        AppEventsHandler::onAppEventNotification($event, common\models\notifications\JournalAddCommentNotification::className());
    },

    'on '. AppEvents::EVENT_JOURNAL_DELETE_COMMENT => function ($event) {
        // Пока решил ничего не делать
    },

    'on '. AppEvents::EVENT_SEND_MESSAGE => function ($event) {
        AppEventsHandler::onAppEventNotification($event, common\models\notifications\dialog\DialogNewMessageNotification::className());
    },

    'on '. AppEvents::EVENT_EMPLOYEE_REGISTRATION_CONFIRMATION => function ($event) {
        AppEventsHandler::onAppEventEmployeeRegistrationConfirmation($event);
    },

	'on '. AppEvents::EVENT_REGISTRATION_LINK_SEND => function ($event) {
		AppEventsHandler::onAppEventRegistrationLinkSend($event);
	},

    'on '. AppEvents::EVENT_USER_SPEND_POINTS => function ($event) {
        AppEventsHandler::onAppEventNotification($event, common\models\notifications\UserOnChangePointsMinusNotification::className());
    },

    'on '. AppEvents::EVENT_USER_EARNING_POINTS => function ($event) {
        AppEventsHandler::onAppEventNotification($event, common\models\notifications\UserOnChangePointsAddNotification::className());
    },

];
