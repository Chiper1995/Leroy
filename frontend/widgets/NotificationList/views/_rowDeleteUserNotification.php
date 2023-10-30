<?php
/* @var \yii\web\View $this */
use yii\bootstrap\Html;

/* @var \common\models\notifications\NewUserRegisterNotification $notification */

?>

<li class="notification NewUserRegisterNotification">
    <div class="icons">
        <p class="icon" style="">
            <?=Html::icon('user')?>
        </p>
    </div>
    <div class="content">
        <h4>Семья удалена</h4>
        <p><b>ФИО: </b><?= Html::encode($notification->initUser->fio) ?></p>
        <p><b>Адрес: </b><?= Html::encode($notification->initUser->address) ?></p>
        <p class="buttons"><?= Html::a('Подробнее &rarr;', ['notification/show', 'id'=>$notification->id, 'url'=>\yii\helpers\Url::to(['user/activate', 'id' => $notification->initUser->id, 'returnUrl'=>Yii::$app->request->url])], ['class' => 'btn btn-primary', 'style' => 'width: 150px;']) ?></p>
    </div>
    <div class="close-notification">
        <?= Html::a(Html::img('/css/img/close-popup.png'), ['notification/close', 'id' => $notification->id], [
            'class' => 'close notification-close'
        ])?>
    </div>
</li>
