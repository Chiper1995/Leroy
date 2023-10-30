<?php
namespace common\models;

use yii\db\ActiveQuery;

class HelpQuery extends ActiveQuery
{
    public function allHelp()
    {
        return $this;
    }
}