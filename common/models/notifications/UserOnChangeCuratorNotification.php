<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 06.06.2018
 * Time: 23:04
 */

namespace common\models\notifications;

use common\rbac\Rights;

class UserOnChangeCuratorNotification extends InitUserNotification
{
    static public $RIGHT = Rights::SHOW_USER_ON_CHANGE_CURATOR_NOTIFICATION;

    static protected $TYPE = 'UserOnChangeCuratorNotification';

}