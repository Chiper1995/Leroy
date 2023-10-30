<?php

use yii\bootstrap\Html;


/* @var $this yii\web\View */
/* @var $model common\models\GoodsShop */

$this->title = 'Добавление магазина покупки товаров';
$this->params['breadcrumbs'][] = ['label' => 'Магазины покупки товаров', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
