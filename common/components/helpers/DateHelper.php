<?php

namespace common\components\helpers;

class DateHelper
{
    //возвращает разницу между текущим временем и датой в днях
    public static function diffDateToday($date)
    {
        $create = new \Datetime(\Yii::$app->formatter->asDateTime($date));
        $today = new \Datetime();
        return date_diff($create, $today)->days;
    }

    //возвращает дату в int, которое на $days меньше, чем сегодня
    public static function dateEarlier($days)
    {
        $today = (new \Datetime())->getTimestamp();     //время сегодня в виде временной метки Unix
        $days = $days * 86400;                          //дни в количестве секунд
        return $today - $days;
    }

}
