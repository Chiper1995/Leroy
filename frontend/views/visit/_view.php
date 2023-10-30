<?php

use common\models\Journal;
use common\models\JournalCheckPhoto;
use common\models\Visit;
use common\models\VisitPhoto;
use common\rbac\Rights;
use frontend\helpers\TimeListToPrettyHtmlTimeList;
use yii\bootstrap\Carousel;
use yii\bootstrap\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Visit */

?>

    <div class="row">
        <div class="col-md-12 content-container view-journal-header" style="padding-bottom: 0; margin-bottom: 15px;">
            <?php
            switch ($model->status) {
                case Visit::STATUS_ON_AGREEMENT:
                    echo Html::tag('div', 'ТРЕБУЕТСЯ СОГЛАСОВАНИЕ', ['class' => 'status-badge left red']);
                    break;
                case Visit::STATUS_TIME_EDITED:
                    echo Html::tag('div', 'ВРЕМЯ ИЗМЕНЕНО', ['class' => 'status-badge left yellow']);
                    break;
                case Visit::STATUS_CANCELED:
                    echo Html::tag('div', 'ОТМЕНЕН', ['class' => 'status-badge left gray']);
                    break;
                case Visit::STATUS_AGREED:
                    echo Html::tag('div', 'СОГЛАСОВАН', ['class' => 'status-badge left green']);
                    break;
            }
            ?>
            <h1 style="margin-top: 30px">Визит</h1>
            <?php if (($model->status == Visit::STATUS_AGREED) && ($model->points > 0)):?>
            <div class="points">
                <?= Html::icon('piggy-bank') ?>
                <p class="value"><?= $model->points ?></p>
                <p class="caption">баллов</p>
            </div>
            <?php //ANDR: Сделать правильную форму слова ?>
            <?php endif;?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 content-container" style="padding-bottom: 0; margin-bottom: 15px;">
            <?= \yii\widgets\DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'user'=>[
                        'attribute' => 'time',
                        'value' => ($model->user->family_name != "") ? "{$model->user->family_name} ({$model->user->fio})" : $model->user->fio,
                        'label' => 'Семья',
                        'visible' => \Yii::$app->user->can(Rights::SHOW_VISITS, []),
                    ],
                    'date:date',
                    'time'=>[
                        'attribute' => 'time',
                        'value' => TimeListToPrettyHtmlTimeList::convert(Visit::getAllTimeNamesList())[$model->time],
                        'format' => 'raw'
                    ],
                    //'updated_at:datetime',
                    'description'=>[
                        'attribute' => 'description',
                        'format' => 'raw',
                        'visible' => strlen($model->description) > 0,
                    ],
                ],
                'template' => '<tr><th style="width: 190px">{label}:</th><td>{value}</td></tr>',
                'options' => ['class' => 'table table-striped table-no-bordered', 'style'=>'margin-bottom: 15px'],
            ]); ?>
        </div>
    </div>


