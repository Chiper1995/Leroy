<?php

/* @var $this yii\web\View */
/* @var $model common\models\ForumTheme */

$this->title = 'Новая тема';
$this->params['breadcrumbs'][] = ['label' => 'Клуб ремонта', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
