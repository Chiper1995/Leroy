<?php

use common\models\Task;
use frontend\widgets\StatusButtonsFilter\StatusButtonsFilter;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\MyTasksSearch */

$this->title = 'Мои задания';
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- Кнопка "Добавить запись" -->
<?php if (Yii::$app->user->can(\common\rbac\Rights::SHOW_MY_JOURNAL_RECORDS)): ?>
    <div class="row" style="padding-top: 5px;">
    <?php if ($dataProvider->count == 0):?>
        <div class="col-md-offset-2 col-sm-offset-2 col-md-8 col-sm-8" style="margin-bottom: 20px;">
            <?= Html::a(Html::icon('plus').'&nbsp;'.'Добавить запись', ['journal/create'], ['class' => 'btn btn-primary',]) ?>
        </div>
    <?php else:?>
        <div class="col-md-12" style="margin-bottom: 20px;">
            <?= Html::a(Html::icon('plus').'&nbsp;'.'Добавить запись', ['journal/create'], ['class' => 'btn btn-primary',]) ?>
        </div>
    <?php endif;?>
    </div>
<?php endif; ?>


<div class="row">
    <div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
        <h1>Мои задания</h1>
    </div>
</div>
<?php \yii\widgets\Pjax::begin(); ?>
<div class="row">
    <div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
        <?= StatusButtonsFilter::widget(['selectedStatus' => $searchModel->status, 'statusList' => Task::getAllStatusNamesList(), 'route' => 'task/my-tasks'])?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
        <div class="task-thumbnails">
            <?php if ($dataProvider->count > 0):?>
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '__taskItem',
                'layout' => '{items}<div class="row"><div class="col-md-12"><div class="text-center">{pager}</div></div></div>',
            ]); ?>
            <?php else:?>
            <div class="no-tasks">
                <p style="font-size: 18px; padding-top: 10px;">Пока нет заданий</p>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>
<?php \yii\widgets\Pjax::end(); ?>
