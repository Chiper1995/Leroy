<?php

use yii\bootstrap\Html;


/* @var $this yii\web\View */
/* @var $model common\models\RoomRepair */

$this->title = 'Добавление помещения для ремонта';
$this->params['breadcrumbs'][] = ['label' => 'Помещения для ремонта', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
