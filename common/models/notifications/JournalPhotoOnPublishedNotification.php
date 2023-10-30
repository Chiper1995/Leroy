<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 15.07.2018
 * Time: 12:17
 */

namespace common\models\notifications;


use common\rbac\Rights;

class JournalPhotoOnPublishedNotification extends JournalNotification
{
    static public $RIGHT = Rights::SHOW_JOURNAL_PHOTO_ON_PUBLISHED_NOTIFICATION;

    static protected $TYPE = 'JournalPhotoOnPublishedNotification';

}