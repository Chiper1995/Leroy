<?php

use common\models\Journal;
use common\models\TaskPhoto;
use common\models\TaskUser;
use common\rbac\Rights;
use yii\bootstrap\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Task */

?>

    <div class="row">
        <div class="col-md-12 content-container view-journal-header" style="padding-bottom: 0; margin-bottom: 15px;">
            <h1><?= Html::encode($model->name) ?></h1>
            <?php if (isset($model->deadline)):?>
              <div style="margin-bottom: 10px;">
                <b><?= $model->getAttributeLabel('deadline') ?>:</b> <?= Yii::$app->formatter->asDate($model->deadline, 'dd.MM.Y'); ?>
              </div>
            <?php endif;?>
        </div>
    </div>

    <?php if (strlen($model->description) > 0):?>
    <div class="row">
        <div class="col-md-12 content-container" style="padding-bottom: 5px; margin-bottom: 15px;">
            <?= $model->description ?>
        </div>
    </div>
    <?php endif;?>

    <?php if (count($model->photos) > 0):?>
    <div class="row">
        <div class="col-md-12 content-container" style="padding-bottom: 0; margin-bottom: 15px;">
            <div class="content-container-caption">
                <h2>Фотографии</h2>
            </div>
            <div class="row photos" id="journal-check-photos">
                <?php foreach ($model->photos as $photoIndex => $photo):?>
                    <?php $photoUrl = TaskPhoto::getUrlPath().'/'.$photo->photo; ?>
                    <div class="col-sm-3 col-md-3 photo">
                        <div class="thumbnail">
                            <a class="im" rel="gallery_task-photos" href="<?php echo $photoUrl ?>">
                                <img src="<?php echo $photo->getPhotoThumb(253, 190) ?>"/>
                            </a>
                        </div>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
    <?php endif;?>

    <?php if (\Yii::$app->user->can(Rights::SHOW_TASKS, [])):?>

    <div class="row">
        <div class="col-md-12 content-container view-task-families" style="padding-bottom: 0; margin-bottom: 15px;">
            <div class="content-container-caption">
                <h2>Семьи</h2>
            </div>
            <div>
                <?php \yii\widgets\Pjax::begin(['enablePushState'=>false, 'id'=>'families-selected-grid-pjax', 'timeout' => false, 'linkSelector' => false]);?>
                <?php
                $searchModel = new \frontend\models\TaskUserSearch();
                $dataProvider = $searchModel->search(
                    $model->id,
                    TaskUser::className(),
                    Yii::$app->request->queryParams,
                    [
                        'pagination' => [
                            'pageSize' => 10,
                            'defaultPageSize' => 10,
                        ],
                        'sort' => [
                            'attributes' => [
                                'fio' => [
                                    'asc' => ['user.fio' => SORT_ASC],
                                    'desc' => ['user.fio' => SORT_DESC],
                                ],
                                'username' => [
                                    'asc' => ['user.username' => SORT_ASC],
                                    'desc' => ['user.username' => SORT_DESC],
                                ]
                            ],
                        ],
                    ]
                );
                $statuses = $searchModel::getAllStatusNamesList();
                ?>
                <?= GridView::widget([
                    'id' => 'families-selected-grid',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'emptyText' => 'Семьи не выбраны',
                    'columns' => [
                        [
                            'attribute' => 'fio',
                            'value' => 'user.fio',
                            'label' => 'ФИО',
                        ],
                        [
                            'attribute' => 'username',
                            'value' => 'user.username',
                            'label' => 'Логин',
                        ],
                        [
                            'attribute' => 'city',
                            'format' => 'html',
                            'label' => 'Город',
                            'value' => function(TaskUser $model) {return implode(',<br/>', \yii\helpers\ArrayHelper::getColumn($model->user->cities, 'name'));},
                            'filter' => \kartik\widgets\Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'city_id',
                                'data' => \common\models\City::getList(),
                                'pluginOptions' => ['allowClear' => false,],
                                'options' => ['multiple' => true],
                            ]),
                            'headerOptions' => [
                                'style' => 'width: 180px;'
                            ]
                        ],
                        [
                            'attribute' => 'task_status_id',
                            'label' => 'Выполнение',
                            'format' => 'raw',
                            'value' => function (TaskUser $taskUser) use ($statuses) {
                                if ($taskUser->journal != null) {
                                    $t = $statuses[$taskUser->journal->status];
									                  $a = 'journal/view';
                                    $class = '';
                                    switch ($taskUser->journal->status) {
                                        case Journal::STATUS_PUBLISHED:
                                        	$class = 'green';
                                        	break;
                                        case Journal::STATUS_ON_CHECK:
                                        	$class = 'yellow';
											                    $a = 'journal/check';
                                        	break;
                                        case Journal::STATUS_DRAFT:
                                        	$class = 'gray';
                                        	break;
                                    }
                                    return Html::a($t, [$a, 'id'=>$taskUser->journal->id, 'returnUrl'=>Yii::$app->request->url], ['class' => 'colored-cell '.$class,]);
                                }
                                $t = $statuses[-$taskUser->status];
                                if ($taskUser->status != TaskUser::STATUS_ACTIVE) {
                                  return Html::tag('span', $t, ['class'=>'colored-cell red']);
                                }
                                return $t;
                            },
                            'filter' => \kartik\widgets\Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'task_status_id',
                                'data' => $statuses,
                                'pluginOptions' => ['allowClear' => false,],
                                'options' => ['multiple' => true],
                            ]),
                        ],
                        [
                            'header' => Html::a(
                                Html::tag('i', '', ['class'=>'fa fa-file-pdf-o']),
                                ['export-task-journal-to-pdf', 'id'=>$model->id],
                                ['target'=>'_blank', 'class'=> 'export-pdf-doc']
                            ) .' '. Html::a(
                                Html::tag('i', '', ['class'=>'fa fa-file-word-o']),
                                ['export-task-journal-to-docx', 'id'=>$model->id],
                                ['target'=>'_blank', 'style'=>'margin-left:10px;', 'class'=> 'export-pdf-doc']
                            ),
                            'headerOptions' => ['style' => 'width:5%;'],
                            'format' => 'raw',
                            'value' => function (TaskUser $taskUser) use ($statuses) {
                                if ($taskUser->journal != null) {
                                    $t = '<i class="fa fa-file-pdf-o"></i>';
                                    $a = 'journal/viewpdf';
                                    $iconWord = '<i class="fa fa-file-word-o"></i>';
                                    $urlDoc = 'journal/viewdoc';
                                    $class = 'export-pdf-doc';
                                    switch ($taskUser->journal->status) {
                                        case Journal::STATUS_PUBLISHED:
                                            $class = 'green export-pdf-doc';
                                            break;
                                    }
                                    $htmlPdf =  Html::a($t, [$a, 'ids'=>$taskUser->journal->id], ['class' => 'colored-cell '.$class]);
                                    $htmlDoc =  Html::a(
                                        $iconWord,
                                        [$urlDoc, 'ids'=>$taskUser->journal->id],
                                        ['class' => 'colored-cell '.$class, 'style'=>'margin-left:10px;']
                                    );
                                    return $htmlPdf.' '. $htmlDoc;

                                }
                                $t = '';

                                return $t;
                            }
                        ],
                    ],
                ]); ?>
                <?php \yii\widgets\Pjax::end(); ?>
            </div>
        </div>
    </div>
    <?php endif;?>

<?= $this->render('_modal_with_image', [
    'model' => $model,
]) ?>

<?= newerton\fancybox\FancyBox::widget([
    'target' => 'a[rel=gallery_task-photos]',
    'helpers' => true,
    'mouse' => true,
    'config' => [
        'maxWidth' => '90%',
        'maxHeight' => '90%',
        'playSpeed' => 7000,
        'padding' => 0,
        'fitToView' => false,
        'width' => '70%',
        'height' => '70%',
        'autoSize' => false,
        'closeClick' => false,
        'openEffect' => 'elastic',
        'closeEffect' => 'elastic',
        'prevEffect' => 'elastic',
        'nextEffect' => 'elastic',
        'closeBtn' => false,
        'openOpacity' => true,
        'helpers' => [
            'title' => ['type' => 'float'],
            'buttons' => [],
            'thumbs' => ['width' => 68, 'height' => 50],
            'overlay' => [
                'locked' => false,
                'css' => [
                    'background' => 'rgba(0, 0, 0, 0.8)'
                ]
            ]
        ],
    ]
]);
