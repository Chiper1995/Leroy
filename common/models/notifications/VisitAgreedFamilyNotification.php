<?php
namespace common\models\notifications;

use common\rbac\Rights;

class VisitAgreedFamilyNotification extends VisitNotification
{
    static public $RIGHT = Rights::SHOW_VISIT_AGREED_FAMILY_NOTIFICATION;

    static protected $TYPE = 'VisitAgreedFamilyNotification';

}