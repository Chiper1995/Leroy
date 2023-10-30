<?php
namespace frontend\controllers;

use Yii;
use common\models\City;
use frontend\models\CitySearch;

/**
 * CityController implements the CRUD actions for City model.
 */
class CityController extends ListDictController
{
    public function getModelClass()
    {
        return City::className();
    }

    public function getModelSearchClass()
    {
        return CitySearch::className();
    }
}
