<?php

use common\models\Help;
use common\rbac\Rights;
use yii\bootstrap\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\HelpSearch */

$this->title = 'Справка';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <div class="row">
        <div class="col-md-6 col-sm-8">
            <h1><?php echo Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-6 col-sm-4 text-right">
            <?php echo  Html::a(Html::icon('plus') . '&nbsp;' . 'Добавить страницу', ['create'], ['class' => 'btn btn-primary',]) ?>
            <?php if (\Yii::$app->user->can(Rights::SHOW_HELP_PRESENTATION)) { ?>
                <?php echo  Html::a('Презентации', ['help/presentation/'], ['class' => 'btn btn-primary',]) ?>
            <?php } ?>
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
                'attribute' => 'default',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    $s = Help::getDefaultList();
                    if (isset($s[$model->default]))
                        return $s[$model->default];
                },
                'filter' => \kartik\widgets\Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'default',
                    'data' => Help::getDefaultList(),
                    'pluginOptions' => ['allowClear' => true,],
                    'options' => ['multiple' => false, 'placeholder' => '',],
                    'hideSearch' => true,


                ]),
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'dd.MM.Y HH:mm:ss'],
                'headerOptions' => [
                    'style' => 'width: 180px;'
                ]
            ],

            ['class' => 'yii\grid\ActionColumn',],
        ],
    ]); ?>
    <?php \yii\widgets\Pjax::end(); ?>

</div>
