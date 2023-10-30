<?php

/**@var Visit $model*/

use common\models\Visit;
use common\rbac\Rights;
use yii\bootstrap\Html;
use yii\helpers\Url;

?>

<div class="thumbnail">
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
    <?php $visitOnAgreement = ($model->status == Visit::STATUS_ON_AGREEMENT)and(Yii::$app->user->can(Rights::SHOW_VISIT_ON_AGREEMENT_NOTIFICATION, ['visit'=>$model]));?>
    <div class="im">
        <a href="<?= Url::to([$visitOnAgreement ? 'visit/agreement' : 'visit/view', 'id'=>$model->id, 'returnUrl'=>Yii::$app->request->url])?>" data-pjax="0">
            <?= Html::tag('div', Html::icon('time'),['class'=>'img-circle']); ?>
        </a>
    </div>
    <div class="caption">
        <h2><a href="<?= Url::to([$visitOnAgreement ? 'visit/agreement' : 'visit/view', 'id'=>$model->id, 'returnUrl'=>Yii::$app->request->url])?>" data-pjax="0"><?= Yii::$app->formatter->asDate($model->date).' '. Visit::getAllTimeNamesList()[$model->time]?></a></h2>
        <div class="caption-buttons">
            <?php if ($visitOnAgreement):?>
                <?= Html::a(Html::icon('eye-open').'<span class="title">Посмотреть</span>', ['visit/agreement', 'id'=>$model->id, 'returnUrl'=>Yii::$app->request->url], ['class'=>'thumbnail-link', 'data-pjax'=>'0'])?>
            <?php else:?>
                <?= Html::a(Html::icon('eye-open').'<span class="title">Посмотреть</span>', ['visit/view', 'id'=>$model->id, 'returnUrl'=>Yii::$app->request->url], ['class'=>'thumbnail-link', 'data-pjax'=>'0'])?>
            <?php endif;?>
        </div>
    </div>
</div>

