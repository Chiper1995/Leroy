<?php
namespace frontend\controllers;

use common\models\WorkRepair;
use frontend\models\WorkRepairSearch;
use Yii;

/**
 * WorkRepairController implements the CRUD actions for City model.
 */
class WorkRepairController extends ListDictController
{
    public function getModelClass()
    {
        return WorkRepair::className();
    }

    public function getModelSearchClass()
    {
        return WorkRepairSearch::className();
    }
}
