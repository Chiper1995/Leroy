<?php
namespace common\models\notifications;

use common\rbac\Rights;

class VisitCanceledFamilyNotification extends VisitNotification
{
    static public $RIGHT = Rights::SHOW_VISIT_CANCELED_FAMILY_NOTIFICATION;

    static protected $TYPE = 'VisitCanceledFamilyNotification';

}