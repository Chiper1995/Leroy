<?php

/* @var $this yii\web\View */
/* @var $model common\models\Visit */

$this->title = 'Добавление визита';
$this->params['breadcrumbs'][] = ['label' => 'Визиты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
