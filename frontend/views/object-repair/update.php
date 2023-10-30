<?php

use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ObjectRepair */

$this->title = 'Редактирование объекта ремонта: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Объекты ремонта', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
