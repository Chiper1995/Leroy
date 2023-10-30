<?php

use common\components\GridView;
use common\models\Visit;
use common\rbac\Rights;
use frontend\helpers\TimeListToPrettyHtmlTimeList;
use kartik\datecontrol\DateControl;
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\VisitSearch */

$this->title = 'Визиты';
$this->params['breadcrumbs'][] = $this->title;

$this->context->layout = 'main';
?>
<div>
    <div class="row">
        <div class="col-md-8 col-sm-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-4 col-sm-4 text-right">
            <?= Html::a(Html::icon('plus').'&nbsp;'.'Добавить', ['create'], ['class' => 'btn btn-primary',]) ?>
        </div>
    </div>

    <?php \yii\widgets\Pjax::begin(); ?>
    <?= GridView::widget([
        'id' => 'visits-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'visible' => \Yii::$app->user->can(Rights::SHOW_ID_COLUMNS),
                'headerOptions' => [
                    'style' => 'width: 80px;'
                ]
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    $s = Visit::getAllStatusNamesList();
                    $sc = [Visit::STATUS_ON_AGREEMENT=>'red', Visit::STATUS_CANCELED=>'gray', Visit::STATUS_AGREED=>'green', Visit::STATUS_TIME_EDITED=>'yellow'];
                    return Html::tag('span', $s[$model->status], ['class'=>'colored-cell '.$sc[$model->status]]);
                },
                'filter' => \kartik\widgets\Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'status',
                    'data' => Visit::getAllStatusNamesList(),
                    'pluginOptions' => ['allowClear' => false,],
                    'options' => ['multiple' => true],
                ]),
            ],
            [
                'attribute' => 'date',
                'format' =>  ['date', 'dd.MM.Y'],
                'headerOptions' => [
                    'style' => 'width: 160px;'
                ],
                'filter' => DateControl::widget([
                    'type'=>DateControl::FORMAT_DATE,
                    'options' => [
                        'options'=>['data'=>['no-filter'=>1],],
                    ],
                    'saveOptions'=>['data'=>['no-filter'=>1]],
                    'model'=>$searchModel,
                    'attribute'=>'date',
                ]),
            ],
            [
                'format' => 'html',
                'attribute' => 'time',
                'value' => function($model, $key, $index, $column) {$a = TimeListToPrettyHtmlTimeList::convert(Visit::getAllTimeNamesList()); return isset($a[$model->time]) ? $a[$model->time] : '';},
                'headerOptions' => [
                    'style' => 'width: 100px;'
                ]
            ],
            [
                'attribute' => 'user.fio',
                'filter' => \kartik\widgets\Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'user_id',
                    'data' => \common\models\Visit::getFamiliesList(),
                    'pluginOptions' => ['allowClear' => false,],
                    'options' => ['multiple' => true],
                ]),
                'headerOptions' => [
                    //'style' => 'width: 200px;'
                ],
            ],
            [
                'attribute' => 'updated_at',
                'format' =>  ['date', 'dd.MM.Y HH:mm:ss'],
                'headerOptions' => [
                    'style' => 'width: 180px;'
                ]
            ],
            [
                'class' => \yii\grid\ActionColumn::className(),
                'template'=>'{view}{agreement-time}{update}{delete}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    $params = is_array($key) ? $key : ['id' => (string) $key];
                    $params['returnUrl'] = Yii::$app->request->url;
                    $params[0] = $action;

                    return Url::toRoute($params);
                },
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        if (!(($model->status == Visit::STATUS_TIME_EDITED)and(Yii::$app->user->can(Rights::SHOW_VISIT_TIME_EDITED_FAMILY_NOTIFICATION, ['visit'=>$model])))) {
                            $options = [
                                'title' => Yii::t('yii', 'View'),
                                'aria-label' => Yii::t('yii', 'View'),
                                'data' => ['pjax' => '0', 'toggle' => 'tooltip', 'placement' => 'bottom',],
                                'class' => 'grid-button'
                            ];
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
                        }
                        else {
                            return '';
                        }
                    },
                    'agreement-time' => function ($url, $model, $key) {
                        if (($model->status == Visit::STATUS_TIME_EDITED)and(Yii::$app->user->can(Rights::SHOW_VISIT_TIME_EDITED_FAMILY_NOTIFICATION, ['visit'=>$model]))) {
                            $options = [
                                'title' => Yii::t('yii', 'Согласовать время'),
                                'aria-label' => Yii::t('yii', 'Согласовать время'),
                                'data' => ['pjax' => '0', 'toggle' => 'tooltip', 'placement' => 'bottom',],
                                'class' => 'grid-button'
                            ];
                            return Html::a('<span class="glyphicon glyphicon-check"></span>', $url, $options);
                        }
                        else {
                            return '';
                        }
                    },
                    'update' => function ($url, $model, $key) {
                        if (\Yii::$app->user->can(Rights::EDIT_VISITS, ['visit'=>$model])) {
                            $options = [
                                'title' => Yii::t('yii', 'Update'),
                                'aria-label' => Yii::t('yii', 'Update'),
                                'data' => ['pjax' => '0', 'toggle' => 'tooltip', 'placement' => 'bottom',],
                                'class' => 'grid-button'
                            ];
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                        }
                        else {
                                return '';
                        }
                    },
                    'delete' => function ($url, $model, $key) {
                        if (\Yii::$app->user->can(Rights::EDIT_VISITS, ['visit'=>$model])) {
                            $options = [
                                'title' => Yii::t('yii', 'Delete'),
                                'aria-label' => Yii::t('yii', 'Delete'),
                                'data' => [
                                    'pjax' => '0',
                                    'method' => 'post',
                                    'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                    'toggle' => 'tooltip',
                                    'placement' => 'bottom',
                                ],
                                'class' => 'grid-button'
                            ];
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
                        }
                        else {
                            return '';
                        }
                    },
                ],
            ],
        ],
    ]); ?>
    <?php \yii\widgets\Pjax::end(); ?>
    <?php $this->registerJs(
<<<JS
$(document).on('change', '#visitsearch-date', function(event) {
    // Выполняем только один раз при изменении
    if ($(this).val() != $(this).attr('data-prev-value')) {
        $(this).attr('data-prev-value', $(this).val());
        $(this).attr('data-no-filter', 0);
        $('#visits-grid').yiiGridView('applyFilter');
        $(this).attr('data-no-filter', 1);
    }
});
JS
    );?>
</div>
