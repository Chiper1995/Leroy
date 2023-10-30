<?php
/* @var \yii\web\View $this */
use yii\bootstrap\Html;

/* @var \common\models\notifications\UserOnChangeCuratorNotification $notification */

?>

<li class="notification UserOnChangeCuratorNotification">
    <div class="icons">
        <p class="icon" style="">
            <?=Html::icon('user')?>
        </p>
    </div>
    <div class="content">
        <div>
            <?php if ($notification->initUser->id == Yii::$app->user->id): ?>
                <h4>Вам назначен куратор</h4>
                <p><b>ФИО: </b><?= Html::encode($notification->initUser->curator->fio); ?></p>
                <p class="buttons"><?= Html::a('Подробнее &rarr;', ['notification/show', 'id'=>$notification->id, 'url'=>\yii\helpers\Url::to(['user/view', 'id' => $notification->initUser->curator_id, 'returnUrl'=>Yii::$app->request->url])], ['class' => 'btn btn-primary', 'style' => 'width: 150px;']) ?></p>
            <?php else: ?>
                <h4>Прикреплена новая семья</h4>
                <p><b>ФИО: </b><?= Html::encode($notification->initUser->fio) ?></p>
                <p class="buttons"><?= Html::a('Подробнее &rarr;', ['notification/show', 'id'=>$notification->id, 'url'=>\yii\helpers\Url::to(['user/family-view', 'id' => $notification->initUser->id, 'returnUrl'=>Yii::$app->request->url])], ['class' => 'btn btn-primary', 'style' => 'width: 150px;']) ?></p>
            <?php endif; ?>
        </div>
    </div>
    <div class="close-notification">
        <?= Html::a(Html::img('/css/img/close-popup.png'), ['notification/close', 'id' => $notification->id], [
            'class' => 'close notification-close'
        ])?>
    </div>
</li>