<?php
namespace frontend\helpers;

use common\models\Goods;
use Yii;
use yii\bootstrap\Html;

class GoodsTreeDataHelper
{
    const cacheKey = 'GoodsTreeDataHelper';

    public static function getAll()
    {
        if (($data = Yii::$app->cache->get(self::cacheKey)) === false) {
            $data = self::getNodes(null);
            Yii::$app->cache->set(self::cacheKey, $data, 864000, Goods::getCacheDependency());
        }

        return $data;
    }

    /**
     * @param Goods $parentItem
     * @return array|null
     */
    static function getNodes($parentItem)
    {
        $res = [];

        /**@var Goods[] $items*/
        if ($parentItem == null) {
            $items = Goods::find()->roots()->with('children')->all();
        }
        else {
            $items = $parentItem->children;
        }

        foreach ($items as $item) {
            if ($item->group) {
                $itemData = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'text' => $item->name,
                    'selectable' => false,
                    'nodes' => self::getNodes($item),
                ];
            }
            else {
                $itemData = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'text' => Html::tag('span', $item->name, ['class'=>'selectable-item', 'data-id'=>$item->id])
                ];
            }

            $res[] = $itemData;
        }
	usort($res, function($a, $b)
	{
		return strcmp($a['name'], $b['name']);
	});
        return $res;
    }
}
