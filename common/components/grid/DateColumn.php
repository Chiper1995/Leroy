<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 19.07.2018
 * Time: 0:12
 */

namespace common\components\grid;

use kartik\datecontrol\DateControl;
use yii\grid\DataColumn;

class DateColumn extends DataColumn
{
    public function init()
    {
        parent::init();
        $this->format = ['date', 'dd.MM.Y'];

        $this->filter = DateControl::widget([
            'model' => $this->grid->filterModel,
            'attribute' => $this->attribute,
            'type' => DateControl::FORMAT_DATE,
        ]);
    }
}