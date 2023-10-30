<?php

use yii\bootstrap\Html;

/* @var \yii\web\View $this */
/* @var common\models\notifications\Notification[] $notifications */
/* @var string $moreUrl */

foreach ($notifications as $notification)
    echo $this->render('_row'.(new \ReflectionClass($notification))->getShortName(), ['notification' => $notification]);
?>
<?php if ($moreUrl !== null): ?>
<li class="notification notification-more">
    <div>
        <p><?= Html::a('Еще...', $moreUrl, ['class' => 'btn btn-primary', 'style' => 'width: 150px;']) ?></p>
    </div>
</li>
<?php endif; ?>
