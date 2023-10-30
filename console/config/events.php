<?php
/**
 * События приложения
 */

use common\events\AppEvents;
use common\events\AppEventsHandler;

return [
    'on '. AppEvents::EVENT_JOURNAL_ON_PUBLISHED => function ($event) {
        AppEventsHandler::onAppEventJournalMakeRewards($event);
        AppEventsHandler::onAppEventNotification($event, common\models\notifications\JournalOnPublishedNotification::className());
    },

    'on '. AppEvents::EVENT_SEND_MAIL_LAST_VISITS => function ($event) {
        AppEventsHandler::onAppEventLastVisitsMail($event);
    },
];
