<?php

namespace common\models\interfaces;

interface IStaticList
{
    public static function getArray();

    public static function getList();

    public static function getIds();

    public static function getName($id);
}