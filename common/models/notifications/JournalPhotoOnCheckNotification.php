<?php
/**
 * Created by PhpStorm.
 * User: arshatilov
 * Date: 02.07.2018
 * Time: 15:45
 */

namespace common\models\notifications;

use common\rbac\Rights;

class JournalPhotoOnCheckNotification extends JournalNotification
{
    static public $RIGHT = Rights::SHOW_JOURNAL_PHOTO_ON_CHECK_NOTIFICATION;

    static protected $TYPE = 'JournalPhotoOnCheckNotification';

}