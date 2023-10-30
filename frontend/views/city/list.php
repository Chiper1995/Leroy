<?php

use common\rbac\Rights;
use yii\bootstrap\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\CitySearch */

$this->title = 'Города';
$this->params['breadcrumbs'][] = $this->title;
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

            'name',
            [
                'attribute' => 'updated_at',
                'format' =>  ['date', 'dd.MM.Y HH:mm:ss'],
                'headerOptions' => [
                    'style' => 'width: 180px;'
                ]
            ],

            ['class' => 'yii\grid\ActionColumn',],
        ],
    ]); ?>
    <?php \yii\widgets\Pjax::end(); ?>

</div>
