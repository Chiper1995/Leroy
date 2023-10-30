<?php
/* @var \yii\web\View $this */
use yii\bootstrap\Html;

/* @var \common\models\notifications\JournalOnCheckNotification $notification */

?>

<li class="notification JournalOnCheckNotification">
    <div class="icons">
        <p class="icon" style="">
            <?=Html::icon('pencil')?>
        </p>
    </div>
    <div class="content">
        <div>
            <h4>Новая запись в дневнике семьи</h4>
            <p><b>ФИО: </b><?= Html::encode($notification->journal->user->fio) ?></p>
            <p><b>Тема: </b><span title="<?= Html::encode($notification->journal->subject) ?>"><?= Html::encode($notification->journal->subject) ?></span></p>
            <p style="padding-bottom: 4px"><?= Html::a('Подробнее &rarr;', ['notification/show', 'id'=>$notification->id, 'url'=>\yii\helpers\Url::to(['journal/check', 'id' => $notification->journal->id, 'returnUrl'=>Yii::$app->request->url])], ['class' => 'btn btn-primary', 'style' => 'width: 150px;']) ?></p>
        </div>
    </div>
    <div class="close-notification">
        <?= Html::a(Html::img('/css/img/close-popup.png'), ['notification/close', 'id' => $notification->id], [
            'class' => 'close notification-close'
        ])?>
    </div>
</li>