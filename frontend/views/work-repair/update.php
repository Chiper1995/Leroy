<?php

use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WorkRepair */

$this->title = 'Редактирование работы: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Работы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
