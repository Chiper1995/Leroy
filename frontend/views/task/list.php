<?php

use common\rbac\Rights;
use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\components\grid\DateColumn;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\TaskSearch */

$this->title = 'Задания';
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
                'attribute' => 'name',
                'value' => function (common\models\Task $data) {
                    return Html::a(Html::encode($data->name), Url::to(['view', 'id' => $data->id, 'returnUrl'=> Yii::$app->request->url]));
                },
                'format' => 'raw',
            ],
            [
                'label' => 'Создал',
                'value' => function (common\models\Task $data) {
                    if (!empty($data->creator->city->name)) {
                        return $data->creator->fio .' '. $data->creator->city->name;
                    } else {
                        return $data->creator->fio;
                    }
                },
                'visible' => \Yii::$app->user->can(Rights::SHOW_ID_COLUMNS),
                'format' => 'raw',
            ],
            [
                'class' => DateColumn::className(),
                'attribute' => 'deadline',
                'headerOptions' => [
                    'style' => 'width: 150px;'
                ]
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
                'template'=>'{delete}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    $params = is_array($key) ? $key : ['id' => (string) $key];
                    $params['returnUrl'] = Yii::$app->request->url;
                    $params[0] = $action;

                    return Url::toRoute($params);
                },
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        if (\Yii::$app->user->can(Rights::EDIT_TASKS, ['task'=>$model])) {
                            $options = [
                                'title' => Yii::t('yii', 'Delete'),
                                'aria-label' => Yii::t('yii', 'Delete'),
                                'data' => ['pjax' => '0', 'toggle' => 'tooltip', 'placement' => 'bottom',],
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

</div>
