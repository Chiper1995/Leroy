<?php

/* @var $this yii\web\View */
/* @var $model common\models\Journal */

$this->title = 'Новая запись';
$this->params['breadcrumbs'][] = ['label' => 'Мои записи', 'url' => ['my-journal']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>

