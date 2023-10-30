<?php
namespace common\models\notifications;

use common\rbac\Rights;

class JournalOnReturnToEditNotification extends JournalNotification
{
    static public $RIGHT = Rights::SHOW_JOURNAL_ON_RETURN_TO_EDIT_NOTIFICATION;

    static protected $TYPE = 'JournalOnReturnToEditNotification';

}