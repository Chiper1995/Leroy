<?php
/* @var \yii\web\View $this */
use common\models\Visit;
use frontend\helpers\TimeListToPrettyHtmlTimeList;
use yii\bootstrap\Html;

/* @var \common\models\notifications\VisitCanceledNotification $notification */

?>

<li class="notification VisitCanceledNotification">
    <div class="icons">
        <p class="icon" style="">
            <?=Html::icon('time')?>
        </p>
    </div>
    <div class="content">
        <div>
            <h4>Визит отменен</h4>
            <p><b>Дата: </b><?= Yii::$app->formatter->format($notification->visit->date, 'date') ?></p>
            <p><b>Время: </b><?$a = TimeListToPrettyHtmlTimeList::convert(Visit::getAllTimeNamesList()); echo isset($a[$notification->visit->time]) ? $a[$notification->visit->time] : ''; ?></p>
            <p style="padding-bottom: 4px"><?= Html::a('Подробнее &rarr;', ['notification/show', 'id'=>$notification->id, 'url'=>\yii\helpers\Url::to(['visit/my-visits', 'returnUrl'=>Yii::$app->request->url])], ['class' => 'btn btn-primary', 'style' => 'width: 150px;']) ?></p>
        </div>
    </div>
    <div class="close-notification">
        <?= Html::a(Html::img('/css/img/close-popup.png'), ['notification/close', 'id' => $notification->id], [
            'class' => 'close notification-close'
        ])?>
    </div>
</li>