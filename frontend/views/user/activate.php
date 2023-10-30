<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Активация семьи: ' . ' ' . $model->fio;
$this->params['breadcrumbs'][] = ['label' => 'Семьи', 'url' => ['families']];
$this->params['breadcrumbs'][] = ['label' => $model->fio];
?>
<div class="user-activate">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= \yii\widgets\DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'family_name',
            'fio',
            'email',
            'phone',
            [
                'label' => 'Город',
                'attribute' => 'cities',
                'value' => implode(', ', \yii\helpers\ArrayHelper::getColumn($model->cities, 'name')), // так как по идее город один у семьи
                'format' => 'html'
            ],
            'address',
            [
                'attribute' => 'repairObjects',
                'value' => $this->render('__property_list', ['items'=>\yii\helpers\ArrayHelper::getColumn($model->repairObjects, 'name')]),
                'format' => 'html'
            ],
            [
                'attribute' => 'repairRooms',
                'value' => $this->render('__property_list', ['items'=>\yii\helpers\ArrayHelper::getColumn($model->repairRooms, 'name')]),
                'format' => 'html'
            ],
            [
                'attribute' => 'repairWorks',
                'value' => $this->render('__property_list', ['items'=>\yii\helpers\ArrayHelper::getColumn($model->repairWorks, 'name')]),
                'format' => 'html'
            ],
        ]
    ]); ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::submitButton('Активировать', ['name' => 'activate', 'class' => 'btn btn-primary btn-with-margin-right',]) ?>
    <?= Html::submitButton('Отменить регистрацию', ['name' => 'delete', 'class' => 'btn btn-danger btn-with-margin-right',]) ?>
    <?= Html::a('Закрыть', Url::to(Yii::$app->user->getReturnUrl(['user/families'])), ['class' => 'btn btn-default', 'name' => 'cancel',]) ?>

    <?php ActiveForm::end(); ?>

</div>
