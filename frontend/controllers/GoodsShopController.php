<?php
namespace frontend\controllers;

use Yii;
use common\models\GoodsShop;
use frontend\models\GoodsShopSearch;

/**
 * GoodsShopController implements the CRUD actions for GoodsShop model.
 */
class GoodsShopController extends ListDictController
{
    public function getModelClass()
    {
        return GoodsShop::className();
    }

    public function getModelSearchClass()
    {
        return GoodsShopSearch::className();
    }
}
