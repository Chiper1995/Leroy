<?php

namespace common\components\helpers;

use common\models\interfaces\IStaticList;
use yii\db\Expression;

class SortHelper
{
    /**
     * @param IStaticList $staticListClass
     * @param string $fieldName
     * @return array
     */
    public static function getAttributeConfigByStaticList($staticListClass, $fieldName)
    {
        $order = "(CASE $fieldName";
        foreach ($staticListClass::getList() as $id => $name) {
            if (is_string($id))
                $id = '\''.$id.'\'';
            $order .= " WHEN $id THEN '$name'";
        }

        $order .= " END)";

        return [
            'asc' => [$order => SORT_ASC],
            'desc' => [$order => SORT_DESC],
        ];
    }
}