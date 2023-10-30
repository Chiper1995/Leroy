<?php
/* @var $this yii\web\View */
/* @var $model common\models\User */

?>

<div class="user-view-detail">
    <?= \yii\widgets\DetailView::widget(
        [
            'model' => $model,
            'attributes' => [
                'phone',
                'email',
            ],
            'template' => '<tr><th>{label}:</th><td>{value}</td></tr>',
            'options' => ['class' => 'table detail-view'],
        ]
    ); ?>
</div>
