<?php
/* @var \yii\web\View $this */

use common\models\notifications\dialog\DialogNewMessageNotification;
use yii\bootstrap\Html;

/* @var \common\models\notifications\UserOnChangePointsAddNotification $notification */
?>

<li class="notification UserOnChangePointsAddNotification">
    <div class="icons">
        <p class="icon" style="">
            <?= Html::icon('piggy-bank') ?>
        </p>
    </div>
    <div class="content">
        <h4><?= Html::encode($notification->getDescription()) ?></h4>
        <p><b>Начисленно баллов: </b><?= Html::encode($notification->getPoints()) ?></p>
    </div>
    <div class="close-notification">
        <?= Html::a(Html::img('/css/img/close-popup.png'), ['notification/close', 'id' => $notification->id], [
            'class' => 'close notification-close'
        ])?>
    </div>
</li>
