<?php

use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Help */

$this->title = 'Редактирование страницы: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Справка', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title];
?>
<div>

    <h1><?php echo Html::encode($this->title) ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
