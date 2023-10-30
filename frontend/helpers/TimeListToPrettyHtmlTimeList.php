<?php
namespace frontend\helpers;

class TimeListToPrettyHtmlTimeList
{
    public static function convert($list)
    {
        $res = [];
        foreach ($list as $key => $item) {
            list($t1, $t2) = explode(' - ', $item, 2);
            list($h1, $m1) = explode(':', $t1, 2);
            list($h2, $m2) = explode(':', $t2, 2);
            $res[$key] = "{$h1}<sup>{$m1}</sup>&nbsp;&mdash;&nbsp;{$h2}<sup>{$m2}</sup>";
        }
        return $res;
    }
}