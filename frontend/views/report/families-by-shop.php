<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\report\FamilyByShopReport */

use common\rbac\Rights;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\grid\GridView;

$this->title = 'Поиск семей по названию магазина';
$this->params['breadcrumbs'][] = ['label' => 'Отчеты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->context->layout = 'main';
?>

<div>
    <div class="row">
        <div class="col-md-12">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
    </div>

    <?php \yii\widgets\Pjax::begin(['id' => 'family-by-shop-report-pjax']); ?>
        <?php $form = ActiveForm::begin([
            'id'=>'familyByShopReportForm',
            'method'=>'GET',
            'action'=>['report/families-by-shop'],
            'options' => ['data-pjax' => true],
            'enableAjaxValidation' => false,
            'validateOnSubmit'=>false,
        ]); ?>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($searchModel, 'shop', [
                    'template' => '<div class="input-group">
                                        {input}
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit">Поиск</button>
                                        </span>
                                    </div>
                                    {error}',
                ])
                    ->input('text', ['class' => 'input-lg form-control', 'placeholder' => $searchModel->getAttributeLabel('shop')]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>

        <?php if ((strlen($searchModel->shop) > 0) && (!$searchModel->hasErrors())) :?>
            <div class="row">
                <div class="col-md-12">
                    <?= Html::a(Html::icon('download').' Скачать XLSX',
                        ['report/families-by-shop-to-excel', 'FamilyByShopReport[shop]' => $searchModel->shop],
                        ['class'=>'btn btn-primary pull-right', 'target' => '_blank', 'data-pjax' => 0]
                    )?>
                </div>
            </div>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'id',
                        'visible' => \Yii::$app->user->can(Rights::SHOW_ID_COLUMNS),
                        'headerOptions' => [
                            'style' => 'width: 80px;'
                        ]
                    ],
                    'family_name' => [
                        'header' => 'Семья',
                        'attribute' => 'family_name'
                    ],
                    'fio',
                    'username',
                    'email',
                    'phone',
                    [
                        'format' => 'html',
                        'header' => 'Город',
                        'value' => function($model) {return implode(',<br/>', \yii\helpers\ArrayHelper::getColumn($model->cities, 'name'));},
                    ],
                ],
            ]); ?>
        <?php endif;?>
    <?php \yii\widgets\Pjax::end(); ?>
</div>