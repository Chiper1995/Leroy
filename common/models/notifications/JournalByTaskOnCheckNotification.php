<?php
namespace common\models\notifications;

use common\rbac\Rights;

class JournalByTaskOnCheckNotification extends JournalNotification
{
    static public $RIGHT = Rights::SHOW_JOURNAL_BY_TASK_ON_CHECK_NOTIFICATION;

    static protected $TYPE = 'JournalByTaskOnCheckNotification';

}