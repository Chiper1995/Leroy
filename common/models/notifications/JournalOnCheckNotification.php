<?php
namespace common\models\notifications;

use common\rbac\Rights;

class JournalOnCheckNotification extends JournalNotification
{
    static public $RIGHT = Rights::SHOW_JOURNAL_ON_CHECK_NOTIFICATION;

    static protected $TYPE = 'JournalOnCheckNotification';

}