<?php
namespace common\models\notifications;

use common\rbac\Rights;

class VisitCanceledNotification extends VisitNotification
{
    static public $RIGHT = Rights::SHOW_VISIT_CANCELED_NOTIFICATION;

    static protected $TYPE = 'VisitCanceledNotification';

}