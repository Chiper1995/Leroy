<?php
/* @var \yii\web\View $this */
use yii\bootstrap\Html;

/* @var \common\models\notifications\dialog\DialogNewMessageNotification $notification */

?>

<li class="notification DialogNewMessageNotification">
    <div class="icons">
        <p class="icon" style="">
            <?=Html::icon('envelope')?>
        </p>
    </div>
    <div class="content">
        <div>
            <h4><?= \Yii::t('app', '{n, plural, one{Новое сообщение} few{У вас # новых сообщения} many{У вас # новых сообщений} other{У вас # новых сообщений}}', ['n' => $notification->count]) ?></h4>
            <p><b>Тема: </b><span title="<?= Html::encode($notification->dialogMessage->dialog->subject) ?>"><?= Html::encode($notification->dialogMessage->dialog->subject) ?></span></p>
            <p><b>Добавил: </b><?= Html::encode($notification->dialogMessage->user->fio) ?></p>
            <p style="padding-bottom: 4px"><?= Html::a('Подробнее &rarr;', [
                    'notification/show',
                    'id'=>$notification->id,
                    'url'=>\yii\helpers\Url::to([
                        'dialog/index',
                        'dialog' => $notification->dialogMessage->dialog->id,
                        'returnUrl'=>Yii::$app->request->url,
                    ])
                ], ['class' => 'btn btn-primary', 'style' => 'width: 150px;']) ?></p>
        </div>
    </div>
    <div class="close-notification">
        <?= Html::a(Html::img('/css/img/close-popup.png'), ['notification/close', 'id' => $notification->id], [
            'class' => 'close notification-close'
        ])?>
    </div>
</li>