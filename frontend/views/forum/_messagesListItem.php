<?php
use \yii\bootstrap\Html;

/**@var \common\models\ForumMessage $model*/

?>

<div class="thumbnail">
    <div class="container-fluid forum-message-row">
        <div class="row">
            <div class="col-md-9 col-sm-12 col-xs-12">
                <h2><?=($model->is_first == 1 ? '' : 'Re: ').Html::encode($model->theme->name); ?></h2>
                <div class="message-info">
                    <b><?=Html::encode(($model->user->family_name != "") ? $model->user->family_name . " (" . $model->user->fio . ")" : $model->user->fio); ?></b> » <?=Yii::$app->formatter->format($model->created_at, ['date', 'dd.MM.Y HH:mm'])?>
                </div>
                <div class="message-content">
                    <?=$model->message?>
                </div>
            </div>
            <div class="col-md-3 hidden-xs hidden-sm user-info">
                <div class="im">
                    <?=\frontend\widgets\UserPhoto\UserPhoto::widget(['user'=>$model->user, 'size'=>166])?>
                </div>
                <div>
                    <b><?=Html::encode(($model->user->family_name != "") ? "{$model->user->family_name} ({$model->user->fio})" : $model->user->fio);?></b>
                    <div><small>Сообщения: <?=$model->user->getForumMessagesCount()?></small></div>
                    <div><small>Зарегистрирован: <?=Yii::$app->formatter->format($model->user->created_at, ['date', 'dd.MM.Y HH:mm'])?></small></div>
                </div>
            </div>
        </div>
    </div>
</div>

