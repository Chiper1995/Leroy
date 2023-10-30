<?php

use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Редактирование пользователя: ' . ' ' . $model->fio;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fio];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
