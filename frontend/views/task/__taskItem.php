<?php

/**@var TaskUser $model*/

use common\models\Journal;
use common\models\TaskUser;
use yii\bootstrap\Html;
use yii\helpers\Url;

?>

<div class="thumbnail">
    <?php
    if ($model->journal != null) {
        switch ($model->journal->status) {
            case Journal::STATUS_DRAFT:
                echo Html::tag('div', 'В ПРОЦЕССЕ', ['class' => 'status-badge left gray']);
                break;
            case Journal::STATUS_ON_CHECK:
                echo Html::tag('div', 'НА ПРОВЕРКЕ', ['class' => 'status-badge left yellow']);
                break;
            case Journal::STATUS_PUBLISHED:
                echo Html::tag('div', 'ВЫПОЛНЕНО', ['class' => 'status-badge left green']);
                break;
        }
    }
    else {
        echo Html::tag('div', 'НОВОЕ', ['class' => 'status-badge left red']);
    }
    ?>
    <div class="im">
        <a href="<?= Url::to(['task/view', 'id'=>$model->task->id, 'returnUrl'=>Yii::$app->request->url])?>" data-pjax="0">
            <?php if (count($model->task->photos) > 0) {
                echo Html::img($model->task->photos[0]->getPhotoThumb(160, 160), ['class'=>'img-circle']);
            }
            else {
                echo Html::tag('div', Html::icon('check'),['class'=>'img-circle']);
            }
            ?>
        </a>
    </div>
    <div class="caption">
        <h2><a href="<?= Url::to(['task/view', 'id'=>$model->task->id, 'returnUrl'=>Yii::$app->request->url])?>" data-pjax="0"><?= $model->task->name?></a></h2>
        <div class="caption-buttons">
            <?= Html::a(Html::icon('eye-open').'<span class="title">Посмотреть</span>', ['task/view', 'id'=>$model->task->id, 'returnUrl'=>Yii::$app->request->url], ['class'=>'thumbnail-link', 'data-pjax'=>'0'])?>
            <?php if ($model->journal != null):?>
                <?php if ($model->journal->status == Journal::STATUS_PUBLISHED):?>
                    <?= Html::a(Html::icon('edit').'<span class="title">Перейти к записи</span>', ['journal/view', 'id'=>$model->journal->id, 'returnUrl'=>Yii::$app->request->url], ['class'=>'thumbnail-link', 'data-pjax'=>'0'])?>
                <?php else:?>
                    <?= Html::a(Html::icon('edit').'<span class="title">Перейти к записи</span>', ['journal/update', 'id'=>$model->journal->id, 'returnUrl'=>Yii::$app->request->url], ['class'=>'thumbnail-link', 'data-pjax'=>'0'])?>
                <?php endif;?>
            <?php else:?>
                <?= Html::a(Html::icon('edit').'<span class="title">Выполнить</span>', ['journal/create-from-task', 'id'=>$model->task->id, 'returnUrl'=>Yii::$app->request->url], ['class'=>'thumbnail-link', 'data-pjax'=>'0'])?>
            <?php endif;?>
        </div>
    </div>
</div>

