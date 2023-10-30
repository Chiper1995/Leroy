<?php
namespace common\models\notifications;

use common\rbac\Rights;

class VisitTimeEditedFamilyNotification extends VisitNotification
{
    static public $RIGHT = Rights::SHOW_VISIT_TIME_EDITED_FAMILY_NOTIFICATION;

    static protected $TYPE = 'VisitTimeEditedFamilyNotification';

}