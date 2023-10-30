<?php

use common\rbac\Rights;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var \common\models\User $model
 */

?>

<div class="family-view-detail">
    <?= \yii\widgets\DetailView::widget(
        [
            'model' => $model,
            'attributes' =>
				ArrayHelper::merge(
                    Yii::$app->user->can(Rights::SHOW_ADMINISTRATOR_FULL_INFO)
                        ? ['statusMessage']: [],
					[
						[
							'attribute' => 'created_at',
							'format' => ['date', 'dd.MM.Y'],
						],
						'username',
                	],
                	Yii::$app->user->can(Rights::SHOW_FAMILIES_PERSONAL_DATA)
						? [
							'email',
							'phone',
							[
								'label' => 'Город',
								'attribute' => 'cities',
								'value' => implode(', ', \yii\helpers\ArrayHelper::getColumn($model->cities, 'name')),
								'format' => 'html'
							],
						]
						: [],
					Yii::$app->user->can(Rights::SHOW_FAMILIES_PERSONAL_DATA) && !empty($model->homeAdress)
						? ['homeAdress.adress']
						: [],
					Yii::$app->user->can(Rights::SHOW_FAMILIES_PERSONAL_DATA) && !empty($model->repairLocations)
						? array_map(static function($a) {return ['label' => 'Адрес другого ремонта', 'value' => $a->adress];}, $model->repairLocations)
						: []
				),
            'template' => '<tr><th>{label}:</th><td>{value}</td></tr>',
            'options' => [
                'class' => 'table detail-view',
                'style' => 'margin-bottom:0px;'
            ],
        ]
    ); ?>
</div>
