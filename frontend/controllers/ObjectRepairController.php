<?php
namespace frontend\controllers;

use common\models\ObjectRepair;
use frontend\models\ObjectRepairSearch;
use Yii;

/**
 * ObjectRepairController implements the CRUD actions for City model.
*/
class ObjectRepairController extends ListDictController
{
    public function getModelClass()
    {
        return ObjectRepair::className();
    }

    public function getModelSearchClass()
    {
        return ObjectRepairSearch::className();
    }
}
