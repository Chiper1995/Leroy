<?php
namespace common\models\notifications;

use common\rbac\Rights;

class NewUserRegisterNotification extends InitUserNotification
{
    static public $RIGHT = Rights::SHOW_NEW_USER_REGISTER_NOTIFICATION;

    static protected $TYPE = 'NewUserRegisterNotification';

}