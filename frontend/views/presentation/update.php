<?php

use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Help */

$this->title = 'Редактирование презентации: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Справка', 'url' => ['help/index']];
$this->params['breadcrumbs'][] = ['label' => 'Презентации', 'url' => ['help/presentation/view']];
$this->params['breadcrumbs'][] = ['label' => $model->title];
?>
<div>

    <h1><?php echo Html::encode($this->title) ?></h1>
    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
