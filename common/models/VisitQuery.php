<?php
namespace common\models;

use yii\db\ActiveQuery;

class VisitQuery extends ActiveQuery
{
    public function allVisits()
    {
        return $this;
    }

    public function myVisits()
    {
        return
            $this
                ->andWhere('date >= CURDATE()')
                ->andWhere('user_id = :user_id', [':user_id' => \Yii::$app->user->id]);
    }

}