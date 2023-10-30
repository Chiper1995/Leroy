<?php
/**
 * ANDR: Проверить необходимость
 * Редактирование профиля семьи
 */

use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Редактирование семьи: ' . ' ' . $model->fio;
$this->params['breadcrumbs'][] = ['label' => 'Семьи', 'url' => ['families']];
$this->params['breadcrumbs'][] = ['label' => $model->fio];
?>
<div class="city-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_familyForm', [
        'model' => $model,
    ]) ?>

</div>
