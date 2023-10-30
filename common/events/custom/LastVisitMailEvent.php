<?php
namespace common\events\custom;

use yii\base\Event;

class LastVisitMailEvent extends Event
{
    public $mailView;
    public $mailRecipient;
    public $mailTitle;
    public $mailOptions;
}
