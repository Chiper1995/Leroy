<?php
/* @var \yii\web\View $this */
use yii\bootstrap\Html;

/* @var \common\models\notifications\JournalOnReturnToEditNotification $notification */

?>

<li class="notification JournalOnReturnToEditNotification">
    <div class="icons">
        <p class="icon" style="">
            <?=Html::icon('remove-sign')?>
        </p>
    </div>
    <div class="content">
        <div>
            <h4 title="Ваша запись возвращена на редактирование">Ваша запись возвращена на редактирование</h4>
            <p><b>Тема: </b><span title="<?= Html::encode($notification->journal->subject) ?>"><?= Html::encode($notification->journal->subject) ?></span></p>
            <p><b>Причина: </b><?= Html::encode($notification->journal->return_reason) ?></p>
            <p style="padding-bottom: 4px"><?= Html::a('Подробнее &rarr;', ['notification/show', 'id'=>$notification->id, 'url'=>\yii\helpers\Url::to(['journal/view', 'id' => $notification->journal->id, 'returnUrl'=>Yii::$app->request->url])], ['class' => 'btn btn-primary', 'style' => 'width: 150px;']) ?></p>
        </div>
    </div>
    <div class="close-notification">
        <?= Html::a(Html::img('/css/img/close-popup.png'), ['notification/close', 'id' => $notification->id], [
            'class' => 'close notification-close'
        ])?>
    </div>
</li>