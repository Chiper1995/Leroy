<?php

/* @var $this yii\web\View */

use yii\bootstrap\Html;

$this->title = 'Отчеты';
$this->params['breadcrumbs'][] = $this->title;

$this->context->layout = 'main';
?>

<div>
    <div class="row">
        <div class="col-md-12">
            <h1><?= Html::tag('i', '', ['class'=>'white fa fa-line-chart']).'&nbsp;&nbsp;'.Html::encode($this->title) ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <ul>
                <li>
                    <h2>Семьи</h2>
                    <ul>
                        <li><?= Html::a('Поиск семей по названию магазина', ['report/families-by-shop'])?></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
