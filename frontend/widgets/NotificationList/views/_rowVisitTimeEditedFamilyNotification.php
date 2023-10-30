<?php
/* @var \yii\web\View $this */
use common\models\Visit;
use frontend\helpers\TimeListToPrettyHtmlTimeList;
use yii\bootstrap\Html;

/* @var \common\models\notifications\VisitTimeEditedFamilyNotification $notification */

?>

<?php
$family = ($notification->visit->user->family_name != "")
	? Html::encode($notification->visit->user->family_name) . " (" . Html::encode($notification->visit->user->fio) . ")"
	: Html::encode($notification->visit->user->fio);

$a = TimeListToPrettyHtmlTimeList::convert(Visit::getAllTimeNamesList());
$dateTime = Yii::$app->formatter->format($notification->visit->date, 'date').', '.(isset($a[$notification->visit->time]) ? $a[$notification->visit->time] : '')
?>

<li class="notification VisitTimeEditedFamilyNotification">
    <div class="icons">
        <p class="icon" style="">
            <?=Html::icon('time')?>
        </p>
    </div>
    <div class="content">
        <div>
            <h4>Семья изменила время визита</h4>
            <p><b>Семья: </b><span title="<?= $family ?>"><?= $family ?></span></p>
            <p><b>Дата, время: </b><?= $dateTime ?></p>
            <p style="padding-bottom: 4px"><?= Html::a('Подробнее &rarr;', ['notification/show', 'id'=>$notification->id, 'url'=>\yii\helpers\Url::to(['visit/agreement-time', 'id' => $notification->visit->id, 'returnUrl'=>Yii::$app->request->url])], ['class' => 'btn btn-primary', 'style' => 'width: 150px;']) ?></p>
        </div>
    </div>
    <div class="close-notification">
        <?= Html::a(Html::img('/css/img/close-popup.png'), ['notification/close', 'id' => $notification->id], [
            'class' => 'close notification-close'
        ])?>
    </div>
</li>