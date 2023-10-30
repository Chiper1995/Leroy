<?php

/* @var $this yii\web\View */
/* @var $model common\models\Visit */

$this->title = 'Редактирование темы';
$this->params['breadcrumbs'][] = ['label' => 'Клуб ремонта', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
