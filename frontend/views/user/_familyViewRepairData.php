<?php
/**
 * @var $this yii\web\View
 * @var \common\models\User $model
 */
?>

<div class="family-view-detail">
    <?= \yii\widgets\DetailView::widget(
        [
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'repairObjects',
                    'value' => $this->render('__property_list', ['items' => \yii\helpers\ArrayHelper::getColumn($model->repairObjects, 'name')]),
                    'format' => 'html'
                ],
                [
                    'attribute' => 'repairRooms',
                    'value' => $this->render('__property_list', ['items' => \yii\helpers\ArrayHelper::getColumn($model->repairRooms, 'name')]),
                    'format' => 'html'
                ],
                [
                    'attribute' => 'repairWorks',
                    'value' => $this->render('__property_list', ['items' => \yii\helpers\ArrayHelper::getColumn($model->repairWorks, 'name')]),
                    'format' => 'html'
                ],
            ],
            'template' => '<tr><th>{label}:</th><td>{value}</td></tr>',
            'options' => ['class' => 'table detail-view'],
        ]
    ); ?>
</div>
