<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 15.07.2018
 * Time: 12:19
 */

namespace common\models\notifications;


use common\rbac\Rights;

class JournalPhotoOnReturnToEditNotification extends JournalNotification
{
    static public $RIGHT = Rights::SHOW_JOURNAL_PHOTO_ON_RETURN_TO_EDIT_NOTIFICATION;

    static protected $TYPE = 'JournalPhotoOnReturnToEditNotification';

}