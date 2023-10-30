<?php

/* @var $this yii\web\View */
/* @var $model common\models\Task */

$this->title = 'Редактирование задания';
$this->params['breadcrumbs'][] = ['label' => 'Задания', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
