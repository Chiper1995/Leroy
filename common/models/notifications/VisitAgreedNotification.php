<?php
namespace common\models\notifications;

use common\rbac\Rights;

class VisitAgreedNotification extends VisitNotification
{
    static public $RIGHT = Rights::SHOW_VISIT_AGREED_NOTIFICATION;

    static protected $TYPE = 'VisitAgreedNotification';

}