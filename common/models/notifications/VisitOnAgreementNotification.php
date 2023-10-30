<?php
namespace common\models\notifications;

use common\rbac\Rights;

class VisitOnAgreementNotification extends VisitNotification
{
    static public $RIGHT = Rights::SHOW_VISIT_ON_AGREEMENT_NOTIFICATION;

    static protected $TYPE = 'VisitOnAgreementNotification';

}