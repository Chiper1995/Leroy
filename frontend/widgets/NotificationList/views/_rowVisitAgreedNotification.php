<?php
/* @var \yii\web\View $this */
use common\models\Visit;
use frontend\helpers\TimeListToPrettyHtmlTimeList;
use yii\bootstrap\Html;

/* @var \common\models\notifications\VisitAgreedNotification $notification */

?>

<li class="notification VisitAgreedNotification">
    <div class="icons">
        <p class="icon" style="">
            <?=Html::icon('time')?>
        </p>
    </div>
    <div class="content">
        <div>
            <h4>Визит подтвержден</h4>
            <p><b>Дата: </b><?= Yii::$app->formatter->format($notification->visit->date, 'date') ?></p>
            <p><b>Время: </b><?$a = TimeListToPrettyHtmlTimeList::convert(Visit::getAllTimeNamesList()); echo isset($a[$notification->visit->time]) ? $a[$notification->visit->time] : ''; ?></p>
            <p style="padding-bottom: 4px"><?= Html::a('Подробнее &rarr;', ['notification/show', 'id'=>$notification->id, 'url'=>\yii\helpers\Url::to(['visit/view', 'id' => $notification->visit->id, 'returnUrl'=>Yii::$app->request->url])], ['class' => 'btn btn-primary', 'style' => 'width: 150px;']) ?></p>
        </div>
    </div>
    <div class="close-notification">
        <?= Html::a(Html::img('/css/img/close-popup.png'), ['notification/close', 'id' => $notification->id], [
            'class' => 'close notification-close'
        ])?>
    </div>
</li>