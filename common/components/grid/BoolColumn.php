<?php

namespace common\components\grid;

use common\models\staticLists\Bool;
use kartik\widgets\Select2;
use yii\grid\DataColumn;
use yii\web\JsExpression;
use yii\web\View;

class BoolColumn extends DataColumn
{
    public function init()
    {
        parent::init();
        $this->value = function ($model){
            return Bool::getName($model->{$this->attribute});
        };

        $this->filter = Select2::widget([
            'model' => $this->grid->filterModel,
            'attribute' => $this->attribute,
            'data' => Bool::getList(),
            'pluginOptions' => [
                'allowClear' => false,
            ],
            'options' => [
                'multiple' => true,
            ],
        ]);
    }
}