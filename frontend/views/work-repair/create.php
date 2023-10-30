<?php

use yii\bootstrap\Html;


/* @var $this yii\web\View */
/* @var $model common\models\WorkRepair */

$this->title = 'Добавление работы';
$this->params['breadcrumbs'][] = ['label' => 'Работы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
