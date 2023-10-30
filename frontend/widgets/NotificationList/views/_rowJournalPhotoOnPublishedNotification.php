<?php
/* @var \yii\web\View $this */
use yii\bootstrap\Html;

/* @var \common\models\notifications\JournalPhotoOnPublishedNotification $notification */

?>

<li class="notification JournalOnPublishedNotification">
    <div class="icons">
        <p class="icon" style="">
            <?=Html::icon('check')?>
        </p>
    </div>
    <div class="content">
        <div>
            <h4>Новые фотографии опубликованы</h4>
            <p style="min-height: 40px;"><b>Тема: </b><span title="<?= Html::encode($notification->journal->subject) ?>"><?= Html::encode($notification->journal->subject) ?></span></p>
            <p style="padding-bottom: 4px"><?= Html::a('Подробнее &rarr;', ['notification/show', 'id'=>$notification->id, 'url'=>\yii\helpers\Url::to(['journal/view', 'id' => $notification->journal->id, 'returnUrl'=>Yii::$app->request->url])], ['class' => 'btn btn-primary', 'style' => 'width: 150px;']) ?></p>
        </div>
    </div>
    <div class="close-notification">
        <?= Html::a(Html::img('/css/img/close-popup.png'), ['notification/close', 'id' => $notification->id], [
            'class' => 'close notification-close'
        ])?>
    </div>
</li>