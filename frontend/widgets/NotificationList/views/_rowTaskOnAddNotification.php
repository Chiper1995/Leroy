<?php
/* @var \yii\web\View $this */
use yii\bootstrap\Html;

/* @var \common\models\notifications\TaskOnAddNotification $notification */

?>

<li class="notification JournalOnCheckNotification">
    <div class="icons">
        <p class="icon" style="">
            <?=Html::icon('check')?>
        </p>
    </div>
    <div class="content">
        <div>
            <h4>У вас новое задание</h4>
            <p style="min-height: 40px;"><b>Название: </b><?= Html::encode($notification->task->name) ?></p>
            <p style="padding-bottom: 4px"><?= Html::a('Подробнее &rarr;', ['notification/show', 'id'=>$notification->id, 'url'=>\yii\helpers\Url::to(['task/view', 'id' => $notification->task->id, 'returnUrl'=>Yii::$app->request->url])], ['class' => 'btn btn-primary', 'style' => 'width: 150px;']) ?></p>
        </div>
    </div>
    <div class="close-notification">
        <?= Html::a(Html::img('/css/img/close-popup.png'), ['notification/close', 'id' => $notification->id], [
            'class' => 'close notification-close'
        ])?>
    </div>
</li>