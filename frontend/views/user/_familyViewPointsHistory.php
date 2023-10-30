<?php
use frontend\models\FamilyPointsHistorySearch;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var $this yii\web\View
 * @var \common\models\User $model
 */

$searchModel = new FamilyPointsHistorySearch(['familyId' => $model->id]);

$dataProviderConfig = [
	'sort' => [
		'attributes' => [
			'date',
		],
		'defaultOrder' => [
			'date' => SORT_DESC,
		]
	]
];
$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $dataProviderConfig);
?>

<?php Pjax::begin(['id'=>'family-grid-pjax']); ?>
<?= GridView::widget([
	'dataProvider' => $dataProvider,
	//'filterModel' => $searchModel,
	'columns' => [
		[
			'label' => $searchModel->getAttributeLabel('date'),
			'attribute' => 'date',
			'format' =>  ['date', 'dd.MM.Y HH:mm:ss'],
			'headerOptions' => [
				'style' => 'width: 180px;'
			]
		],
		[
			'label' => $searchModel->getAttributeLabel('points'),
			'attribute' => 'points',
			'headerOptions' => [
				'style' => 'width: 80px;'
			]
		],
		[
			'label' => $searchModel->getAttributeLabel('user'),
			'attribute' => 'user',
			'headerOptions' => [
				'style' => 'width: 80px;'
			]
		],
		[
			'label' => $searchModel->getAttributeLabel('comment'),
			'attribute' => 'comment',
			'format' => 'raw',
			'value' => function($model) {
				switch ($model['type']) {
					case 1: return \yii\bootstrap\Html::a($model['comment'], ['journal/view', 'id' => $model['id']], ['target' => '_blank', 'data-pjax' => '0']); break;
					case 2: return \yii\bootstrap\Html::a($model['comment'], ['visit/view', 'id' => $model['id']], ['target' => '_blank', 'data-pjax' => '0']); break;
					case 5: return \yii\bootstrap\Html::a($model['comment'], ['journal/view', 'id' => $model['id']], ['target' => '_blank', 'data-pjax' => '0']); break;
					case 6: return \yii\bootstrap\Html::a($model['comment'], ['journal/view', 'id' => $model['id']], ['target' => '_blank', 'data-pjax' => '0']); break;
					default: return \yii\bootstrap\Html::encode($model['comment']);
				}

			},
			'headerOptions' => [
				'style' => 'width: 80px;'
			]
		],
	],
]); ?>
<?php Pjax::end(); ?>
