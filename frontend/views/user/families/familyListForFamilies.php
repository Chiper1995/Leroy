<?php

use common\components\ScrollPager;
use yii\bootstrap\ActiveForm;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \frontend\models\user\FamiliesForFamilySearch */
/* @var $form ActiveForm */


$this->title = 'Семьи';
$this->params['breadcrumbs'][] = $this->title;
$this->context->layout = 'mainEmpty';
?>

<div class="family-list-for-families">
	<div class="row">
		<div class="col-md-12">
			<?php $form = ActiveForm::begin([
				'id'=>'familyListForFamiliesFilterForm',
				'method'=>'GET',
				'action'=>['user/families-search'],
				'enableAjaxValidation' => false,
			]); ?>
				<?= $form->field($searchModel, 'search', [
						'template' => '<div class="input-group">{input}<span class="input-group-btn"><button class="btn btn-default" type="submit">Найти</button></span></div>{hint}{error}',
					])
					->textInput(['placeholder' => 'Введите имя или регион'])
					->label(false) ?>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
	<?php if ($dataProvider->count == 0):?>
		<div class="row" style="padding-top: 5px;">
			<div class="col-md-offset-2 col-sm-offset-2 col-md-8 col-sm-8 content-container text-center">
				<p style="font-size: 18px; padding-top: 10px;">:(<br/>Мы ничего не нашли</p>
			</div>
		</div>
	<?php else:?>
		<div class="row" style="padding-top: 5px;">
			<div class="thumbnails">
				<?= ListView::widget([
					'dataProvider' => $dataProvider,
					'itemView' => '_familyListForFamiliesItem',
					'itemOptions' => ['class' => 'col-md-4 col-sm-6 item'],
					'layout' => '<div class="row"><div class="col-md-12">{items}</div></div><div class="row"><div class="col-md-12"><div class="text-center">{pager}</div></div></div>',
					'pager' => [
						'class' => ScrollPager::className(),
						'spinnerSrc' => '/css/img/loading.gif',
						'triggerText' => 'Показать ещё',
						'triggerTemplate' => '<div class="ias-trigger" style="text-align:center;cursor:pointer;"><a class="btn btn-primary">{text} <span class="glyphicon glyphicon-chevron-down"></span></a></div>',
						'noneLeftText' => '',
						'triggerOffset' => 5,
						'historyPrev' => '.prev a',
						'triggerTextPrev' => 'Показать ещё',
						'triggerTemplatePrev' => '<div class="ias-trigger" style="text-align:center;cursor:pointer;"><a class="btn btn-primary">{text} <span class="glyphicon glyphicon-chevron-up"></span></a></div>',
						'enabledExtensions' => [
							ScrollPager::EXTENSION_TRIGGER,
							ScrollPager::EXTENSION_SPINNER,
							ScrollPager::EXTENSION_PAGING,
							ScrollPager::EXTENSION_HISTORY
						],
						'addControlsToParent' => true,
					]
				]); ?>
			</div>
		</div>
	<?php endif;?>
</div>
