<?php

use common\models\Help;
use common\rbac\Rights;
use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\PresentationSearch */

$this->title = 'Презентации';
$this->params['breadcrumbs'][] = ['label' => 'Справка', 'url' => ['help/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <div class="row">
        <div class="col-md-8 col-sm-8">
            <h1><?php echo Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-4 col-sm-4 text-right">
            <?php echo Html::a(Html::icon('plus').'&nbsp;'.'Добавить презентацию', ['create'], ['class' => 'btn btn-primary',]) ?>
        </div>
    </div>

    <?php \yii\widgets\Pjax::begin(); ?>
    <?php echo GridView::widget([
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

            'title',
            [
                'attribute' => 'updated_at',
                'format' =>  ['date', 'dd.MM.Y HH:mm:ss'],
                'headerOptions' => [
                    'style' => 'width: 180px;'
                ]
            ],
            [
                'attribute' => 'help_id',
                'value' => function (\common\models\Presentation $data) {
                    if($data->help && $data->help->id)
                    return Html::a(Html::encode($data->help->title), Url::to(['help/view', 'id' => $data->help->id]));
                },
                'format' => 'raw',
            ],

            ['class' => 'yii\grid\ActionColumn',],
        ],
    ]); ?>
    <?php \yii\widgets\Pjax::end(); ?>

</div>
