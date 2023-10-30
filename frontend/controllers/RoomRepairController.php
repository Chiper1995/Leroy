<?php
namespace frontend\controllers;

use common\models\RoomRepair;
use frontend\models\RoomRepairSearch;
use Yii;

/**
 * RoomRepairController implements the CRUD actions for City model.
 */
class RoomRepairController extends ListDictController
{
    public function getModelClass()
    {
        return RoomRepair::className();
    }

    public function getModelSearchClass()
    {
        return RoomRepairSearch::className();
    }
}
