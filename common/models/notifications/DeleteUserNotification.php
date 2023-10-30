<?php
namespace common\models\notifications;

use common\rbac\Rights;

class DeleteUserNotification extends InitUserNotification
{
    static public $RIGHT = Rights::SHOW_USER_DELETE_NOTIFICATION;

    static protected $TYPE = 'DeleteUserNotification';
}
