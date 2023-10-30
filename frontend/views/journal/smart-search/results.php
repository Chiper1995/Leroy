<?php

use common\components\ScrollPager;
use common\rbac\Rights;
use yii\bootstrap\ActiveForm;
use yii\widgets\ListView;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider|null */
/* @var $searchModel frontend\models\journal\AllJournalSmartSearch|frontend\models\journal\UserAllJournalSmartSearch */

$this->title = 'Результаты поиска';
$this->params['breadcrumbs'][] = $this->title;
$request = Yii::$app->request;
$AllJournalSmartSearch = $request->get('AllJournalSmartSearch');
$UserAllJournalSmartSearch = $request->get('UserAllJournalSmartSearch');
if (empty($searchModel->typeFilter) && !empty($AllJournalSmartSearch['typeFilter']))
    $searchModel->typeFilter = $AllJournalSmartSearch['typeFilter'];
elseif (empty($searchModel->typeFilter) && !empty($UserAllJournalSmartSearch['typeFilter']))
    $searchModel->typeFilter = $UserAllJournalSmartSearch['typeFilter'];
?>

<?= $this->render('_filter', ['searchModel' => $searchModel]) ?>
<?php if ($searchModel->typeFilter == 'userJournals'): ?>
    <!-- фильтры -->
    <?php if (Yii::$app->user->can(\common\rbac\Rights::FILTER_FEED_CURATOR)): ?>
        <?= $this->render('//journal/_filterFeedCurator', ['searchModel' => $searchModel]); ?>
    <?php endif; ?>
    <?php if (Yii::$app->user->can(\common\rbac\Rights::FILTER_FEED_USER)): ?>
        <?= $this->render('//journal//_filterFeedUser', ['searchModel' => $searchModel]); ?>
    <?php endif; ?>
<?php elseif ($searchModel->typeFilter == 'allJournals'): ?>
    <?= $this->render('//journal/_filterAllJournals', ['searchModel' => $searchModel]) ?>
<?php endif; ?>
<?php if (($dataProvider === null) || ($dataProvider->count == 0)): ?>
    <div class="row" style="padding-top: 5px;">
        <div class="col-md-offset-2 col-sm-offset-2 col-md-8 col-sm-8 content-container text-center">
            <p style="font-size: 18px; padding-top: 10px;">:(<br/>Нет записей</p>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-md-4">
            <h4>Найдено записей: <?= $dataProvider->totalCount; ?></h4>
        </div>
        <?php if (\Yii::$app->user->can(Rights::SHOW_FAMILIES)): ?>
            <div class="col-md-8">
                <?= Html::a(Html::icon('download') . ' Скачать DOCX', '#', ['class' => 'btn btn-primary pull-right export-docx', 'target' => '_blank']); ?>
                <?= Html::a(Html::icon('download') . ' Скачать PDF', '#', ['class' => 'btn btn-primary pull-right export-pdf btn-with-margin-right', 'target' => '_blank']); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="row" style="padding-top: 15px;">
        <div class="col-md-12">
            <div class="content-container">
                <?= ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => '_item',
                    'itemOptions' => ['class' => 'col-md-12 item'],
                    'viewParams' => [],
                    'layout' => '<div class="row smart-search-results">{items}</div><div class="row"><div class="col-md-12"><div class="text-center">{pager}</div></div></div>',
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
                            //ScrollPager::EXTENSION_NONE_LEFT,
                            ScrollPager::EXTENSION_PAGING,
                            ScrollPager::EXTENSION_HISTORY
                        ],
                        'addControlsToParent' => true,
                        'eventOnLoaded' => 'function(data, items){$(\'.pagination\').html($(data).find(\'.pagination\').html());}',
                    ]
                ]); ?>
                <div style="margin-top: 15px; text-align: center;">
                    <?= \yii\widgets\LinkPager::widget(['pagination' => $dataProvider->pagination]); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $form = ActiveForm::begin([
    'id' => 'smartSearchExportToPdfForm',
    'method' => 'GET',
    'action' => ['journal/smart-search-export-to-pdf'],
    'options' => ['target' => '_blank', 'data-pjax' => false],
    'enableAjaxValidation' => false,
    'validateOnSubmit' => false,
]); ?>
<?= $form->field($searchModel, 'smartSearch')->hiddenInput()->label(false); ?>
<?= $form->field($searchModel, 'workRepair')->hiddenInput()->label(false); ?>
<?= $form->field($searchModel, 'type')->hiddenInput()->label(false); ?>
<?= $form->field($searchModel, 'roomRepair')->hiddenInput()->label(false); ?>
<?= $form->field($searchModel, 'city')->hiddenInput()->label(false); ?>
<input type="hidden" name="withImages" class="withImages" value="0"/>
<?php ActiveForm::end(); ?>

<?php $form = ActiveForm::begin([
    'id' => 'smartSearchExportToDocxForm',
    'method' => 'GET',
    'action' => ['journal/smart-search-export-to-docx'],
    'options' => ['target' => '_blank', 'data-pjax' => false],
    'enableAjaxValidation' => false,
    'validateOnSubmit' => false,
]); ?>
<?= $form->field($searchModel, 'smartSearch')->hiddenInput()->label(false); ?>
<?= $form->field($searchModel, 'workRepair')->hiddenInput()->label(false); ?>
<?= $form->field($searchModel, 'type')->hiddenInput()->label(false); ?>
<?= $form->field($searchModel, 'roomRepair')->hiddenInput()->label(false); ?>
<?= $form->field($searchModel, 'city')->hiddenInput()->label(false); ?>
<input type="hidden" name="withImages" class="withImages" value="0"/>
<?php ActiveForm::end(); ?>

<?php if (\Yii::$app->user->can(Rights::SHOW_FAMILIES)): ?>
    <?php $this->registerJs(<<<'JS'
		jQuery(document).ready(function () {
			$('body')
				.on('click', '.export-pdf', function () {
					runExport($("#smartSearchExportToPdfForm"));
					return false;
				})
				.on('click', '.export-docx', function () {
					runExport($("#smartSearchExportToDocxForm"));
					return false;
				});

			function runExport($form) {
				bootbox.dialog({
					message: "Скачать записи дневников с картинками?",
					title: "Подтверждение",
					buttons: {
						yes: {
							label: "Да",
							className: "btn-primary btn-with-margin-right",
							callback: function () {
								$form.find(".withImages").val(1);
								$form.submit();
							}
						},
						no: {
							label: "Нет",
							className: "btn-default",
							callback: function () {
								$form.find(".withImages").val(0);
								$form.submit();
							}
						}
					}
				});
			}
		});
JS
    ); ?>
<?php endif; ?>
