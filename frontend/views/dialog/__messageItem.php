<?php
use common\models\Dialog;
use yii\bootstrap\Html;

/**@var Dialog $model*/
?>

<div class="media message-item">
    <div class="media-left">
        <?=\frontend\widgets\UserPhoto\UserPhoto::widget(['user'=>$model['user'], 'size'=>60])?>
    </div>
    <div class="media-body">
        <div class="row">
            <div class="col-sm-9 col-md-9">
                <div class="message-author"><?= Html::encode($model['user']->fio).' ('.Html::encode($model['user']->username).')'?></div>
            </div>
            <div class="col-sm-3 col-md-3">
                <?php echo Html::tag('b',Html::encode($model['time']),['id'=>'timestamp','style'=>'display: none;']);?>
                <div class="message-time"><?= Yii::$app->formatter->format($model['time'], ['date', 'dd.MM.Y HH:mm:ss'])?>
                </div>
            </div>
        </div>
        <div class="message-messages">
            <?php foreach($model['message'] as $message):?>
                <p><?= $message?></p>
            <?php endforeach;?>
        </div>
    </div>
</div>

