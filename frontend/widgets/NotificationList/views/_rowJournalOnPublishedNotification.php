<?php
/* @var \yii\web\View $this */
use yii\bootstrap\Html;

/* @var \common\models\notifications\JournalOnPublishedNotification $notification */

?>

<li class="notification JournalOnPublishedNotification">
    <div class="icons">
        <p class="icon" style="">
            <?=Html::icon('piggy-bank')?>
        </p>
    </div>
    <div class="content">
        <h4>Ваша запись проверена и опубликована</h4>
        <p><b>Тема: </b><span class="text-right" title="<?= Html::encode($notification->journal->subject) ?>"><?= Html::encode($notification->journal->subject) ?></span></p>
        <p><b>Начислено баллов: </b><?= Html::encode($notification->journal->points) ?></p>
        <p class="button-more"><?= Html::a('Подробнее &rarr;', ['notification/show', 'id'=>$notification->id, 'url'=>\yii\helpers\Url::to(['journal/view', 'id' => $notification->journal->id, 'returnUrl'=>Yii::$app->request->url])], ['class' => 'btn btn-primary', 'style' => 'width: 150px;']) ?></p>
    </div>
    <div class="close-notification">
        <?= Html::a(Html::img('/css/img/close-popup.png'), ['notification/close', 'id' => $notification->id], [
            'class' => 'close notification-close'
        ])?>
    </div>
</li>