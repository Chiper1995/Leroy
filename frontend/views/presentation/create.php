<?php

use yii\bootstrap\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Help */

$this->title = 'Добавление презентации';
$this->params['breadcrumbs'][] = ['label' => 'Справка', 'url' => ['help/index']];
$this->params['breadcrumbs'][] = ['label' => 'Презентации', 'url' => ['help/presentation/']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>

    <h1><?php echo Html::encode($this->title) ?></h1>
    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
