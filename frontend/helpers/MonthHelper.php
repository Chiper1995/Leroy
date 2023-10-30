<?php
namespace frontend\helpers;

use Yii;

class MonthHelper
{
    public static function getMonth($monthNumber)
    {
        $months = [
            1=>'янв',
            2=>'фев',
            3=>'мар',
            4=>'апр',
            5=>'мая',
            6=>'июн',
            7=>'июл',
            8=>'авг',
            9=>'сен',
            10=>'окт',
            11=>'ноя',
            12=>'дек',
        ];

        return $months[$monthNumber];
    }
}