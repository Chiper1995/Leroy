<?php
namespace common\components;

use Yii;
use yii\helpers\Url;

/**
 * Добавил исключение полей с data-no-filter из прицепления обработчика и отправки в форме фильтра
 *
 * Class GridView
 * @package common\components
 */
class GridView extends \yii\grid\GridView
{
    protected function getClientOptions()
    {
        $filterUrl = isset($this->filterUrl) ? $this->filterUrl : Yii::$app->request->url;
        $id = $this->filterRowOptions['id'];
        $filterSelector = "#$id input:not(input[data-no-filter=1]), #$id select:not(select[data-no-filter=1])";
        if (isset($this->filterSelector)) {
            $filterSelector = $this->filterSelector;
        }

        return [
            'filterUrl' => Url::to($filterUrl),
            'filterSelector' => $filterSelector,
        ];
    }

}