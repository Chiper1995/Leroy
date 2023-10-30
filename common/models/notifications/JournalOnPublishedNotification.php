<?php
namespace common\models\notifications;

use common\rbac\Rights;

class JournalOnPublishedNotification extends JournalNotification
{
    static public $RIGHT = Rights::SHOW_JOURNAL_ON_PUBLISHED_NOTIFICATION;

    static protected $TYPE = 'JournalOnPublishedNotification';

}