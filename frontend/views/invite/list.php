<?php

use common\models\City;
use common\models\Invite;
use common\models\ObjectRepair;
use frontend\models\InvitesSearch;
use kartik\widgets\Select2;
use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel InvitesSearch */

$this->title = 'Анкеты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <div class="row">
        <div class="col-sm-4">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-sm-8 text-right">
            <?= Html::a(Html::icon('download').' Скачать XLSX', ['invite/export'], ['class'=>'btn btn-default', 'id'=>'export-btn'])?>
        </div>
    </div>
    <div class="row" style="margin-top: 15px;">
        <div class="col-sm-12">
            <div class="well">
                Прокручивать таблицу влево-вправо можно скроллом мыши, зажав <kbd>Shift</kbd> на клавиатуре
            </div>
        </div>
    </div>

    <?php Pjax::begin(['id'=>'invite-grid-pjax']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{delete}{view}{send-registration-email}',
				'buttons' => [
					'send-registration-email' => function ($url, Invite $model, $key) {
						$url = Url::to(['invite/send-registration-email', 'id'=>$model->id, 'returnUrl'=>Yii::$app->request->url]);

						$options = [
							'title' => Yii::t('yii', 'Отправить ссылку на регистрацию'),
							'aria-label' => Yii::t('yii', 'Отправить ссылку на регистрацию'),
							'data' => [
								'toggle' => 'tooltip',
								'placement' => 'top',
							],
							'class' => 'grid-button'
						];

						if (empty($model->email)) {
							$options['onclick'] = "yii.error('Нужно заполнить Email');";
						}
						else if ($model->status === Invite::STATUS_REGISTERED) {
							$options['onclick'] = "yii.error('Семья уже зарегистрировалась');";
						}
						else {
							$options['onclick'] = "
								yii.confirm(
									'Вы уверены, что хотите отправить email со ссылкой на регистрацию семье?', 
									function() {
										$.ajax('$url', {
											type: 'POST'
										}).done(function(data) {
											$.pjax.reload({container: '#invite-grid-pjax'});
										});
									}
								);";
						}

						$options['onclick'] .= "return false;";

						return Html::a('<span class="glyphicon glyphicon-envelope"></span>', '#', $options);
					},
				],
            ],
            [
                'attribute' => 'id',
                'label' => 'ID',
                'headerOptions' => [
                    'style' => 'min-width: 80px;'
                ]
            ],
			[
				'attribute' => 'status',
				'label' => 'Статус',
				'value' => function(Invite $model){return ArrayHelper::getValue(Invite::$STATUS_LIST, $model->status);},
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'status',
					'data' => Invite::$STATUS_LIST,
					'pluginOptions' => ['allowClear' => false,],
					'options' => ['multiple' => true],
				]),
				'content' => function (Invite $model) {
    				$value = $model->status === null
						? '<span class="not-set">(не задано)</span>'
						: ArrayHelper::getValue(Invite::$STATUS_LIST, $model->status);

    				if ($model->status === Invite::STATUS_REGISTERED) {
						return $value;
					}
    				else {
						return Html::a($value, '#', [
								'onclick' => 'return false;',
								'data' => [
									'id' => $model->id,
									'status' => $model->status,
									'toggle' => 'tooltip',
									'placement' => 'top',
								],
								'class' => 'edit-status-btn grid-editable-link',
								'title' => 'Редактировать',
						]);
					}
				},
				'headerOptions' => [
					'style' => 'min-width: 180px;'
				]
			],
            [
                'attribute' => 'fio',
                'label' => 'Как называть',
                'headerOptions' => [
                    'style' => 'min-width: 140px;'
                ]
            ],
            [
                'attribute' => 'phone',
                'label' => 'Телефон',
                'headerOptions' => [
                    'style' => 'min-width: 140px;'
                ]
            ],
            [
                'attribute' => 'email',
                'label' => 'Email',
                'headerOptions' => [
                    'style' => 'min-width: 140px;'
                ],
				'format' => 'raw',
                'content' => function (Invite $model) {
    				$value = empty($model->email)
						? '<span class="not-set">(не задано)</span>'
						: $model->email;

    				return Html::a($value, '#', [
    						'onclick' => 'return false;',
							'data' => [
								'id' => $model->id,
								'email' => $model->email,
								'toggle' => 'tooltip',
								'placement' => 'top',
							],
							'class' => 'edit-email-btn grid-editable-link',
							'title' => 'Редактировать',
					]);
				}
            ],
			[
				'attribute' => 'city_id',
				'label' => 'Город',
				'value' => function(Invite $model){return $model->city !== null ? $model->city->name : null;},
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'city_id',
					'data' => City::getList(),
					'pluginOptions' => ['allowClear' => false,],
					'options' => ['multiple' => true],
				]),
				'headerOptions' => [
					'style' => 'min-width: 180px;'
				]
			],
			[
				'attribute' => 'city_other',
				'label' => 'Город: другой',
				'headerOptions' => [
					'style' => 'min-width: 140px;'
				]
			],
			[
				'attribute' => 'sex',
				'label' => 'Пол',
				'value' => function(Invite $model){return ArrayHelper::getValue(Invite::$L_SEX, $model->sex);},
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'sex',
					'data' => Invite::$L_SEX,
					'pluginOptions' => ['allowClear' => false,],
					'options' => ['multiple' => true],
				]),
				'headerOptions' => [
					'style' => 'min-width: 80px;'
				]
			],
            [
                'attribute' => 'age',
                'label' => 'Возраст',
                'headerOptions' => [
                    'style' => 'min-width: 80px;'
                ]
            ],
			[
				'attribute' => 'family',
				'label' => 'Семейное положение',
				'value' => function(Invite $model){return ArrayHelper::getValue(Invite::$L_FAMILY, $model->family);},
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'family',
					'data' => Invite::$L_FAMILY,
					'pluginOptions' => ['allowClear' => false,],
					'options' => ['multiple' => true],
				]),
				'headerOptions' => [
					'style' => 'min-width: 240px;'
				]
			],
			[
				'attribute' => 'children',
				'label' => 'Есть ли дети',
				'value' => function(Invite $model){return ArrayHelper::getValue(Invite::$L_HAVE_CHILDREN, $model->children);},
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'children',
					'data' => Invite::$L_HAVE_CHILDREN,
					'pluginOptions' => ['allowClear' => false,],
					'options' => ['multiple' => true],
				]),
				'headerOptions' => [
					'style' => 'min-width: 80px;'
				]
			],
			[
				'attribute' => 'repair_status',
				'label' => 'Ремонт',
				'value' => function(Invite $model){return ArrayHelper::getValue(Invite::$L_REPAIR_STATUS, $model->repair_status);},
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'repair_status',
					'data' => Invite::$L_REPAIR_STATUS,
					'pluginOptions' => ['allowClear' => false,],
					'options' => ['multiple' => true],
				]),
				'headerOptions' => [
					'style' => 'min-width: 240px;'
				]
			],
			[
				'attribute' => 'repair_when_finish',
				'label' => 'Завершение ремонта',
				'value' => function(Invite $model){return ArrayHelper::getValue(Invite::$L_REPAIR_WHEN_FINISH, $model->repair_when_finish);},
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'repair_when_finish',
					'data' => Invite::$L_REPAIR_WHEN_FINISH,
					'pluginOptions' => ['allowClear' => false,],
					'options' => ['multiple' => true],
				]),
				'headerOptions' => [
					'style' => 'min-width: 240px;'
				]
			],
			[
				'attribute' => 'typeOfRepair',
				'label' => 'Планируемые работы',
				'format' => 'html',
				'value' => function(Invite $model){
    				return count($model->typeOfRepair) > 0 ? implode('<br/>', array_map(function($v){return '- ' . $v;}, $model->typeOfRepair)) : null;
				},
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'typeOfRepair',
					'data' => Invite::$L_TYPE_OF_REPAIR,
					'pluginOptions' => ['allowClear' => false,],
					'options' => ['multiple' => true],
				]),
				'headerOptions' => [
					'style' => 'min-width: 180px;'
				]
			],
			[
				'attribute' => 'repairObject',
				'label' => 'Объект ремонта',
				'format' => 'html',
				'value' => function(Invite $model){
    				return count($t = $model->getRepairObjectsText()) > 0 ? implode('<br/>', array_map(function($v){return '- ' . $v;}, $t)) : null;
				},
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'repairObject',
					'data' => ObjectRepair::getList(),
					'pluginOptions' => ['allowClear' => false,],
					'options' => ['multiple' => true],
				]),
				'headerOptions' => [
					'style' => 'min-width: 180px;'
				]
			],
			[
				'attribute' => 'have_cottage',
				'label' => 'Есть ли дача',
				'value' => function(Invite $model){return ArrayHelper::getValue(Invite::$L_HAVE_COTTAGE, $model->have_cottage);},
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'have_cottage',
					'data' => Invite::$L_HAVE_COTTAGE,
					'pluginOptions' => ['allowClear' => false,],
					'options' => ['multiple' => true],
				]),
				'headerOptions' => [
					'style' => 'min-width: 80px;'
				]
			],
			[
				'attribute' => 'plan_cottage_works',
				'label' => 'Работы на даче',
				'value' => function(Invite $model){return ArrayHelper::getValue(Invite::$L_PLAN_COTTAGE_WORKS, $model->plan_cottage_works);},
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'plan_cottage_works',
					'data' => Invite::$L_PLAN_COTTAGE_WORKS,
					'pluginOptions' => ['allowClear' => false,],
					'options' => ['multiple' => true],
				]),
				'headerOptions' => [
					'style' => 'min-width: 80px;'
				]
			],
			[
				'attribute' => 'who_worker',
				'label' => 'Кто выполняет работы',
				'value' => function(Invite $model){return ArrayHelper::getValue(Invite::$L_WHO_WORKER, $model->who_worker);},
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'who_worker',
					'data' => Invite::$L_WHO_WORKER,
					'pluginOptions' => ['allowClear' => false,],
					'options' => ['multiple' => true],
				]),
				'headerOptions' => [
					'style' => 'min-width: 220px;'
				]
			],
			[
				'attribute' => 'who_chooser',
				'label' => 'Кто решает',
				'value' => function(Invite $model){return ArrayHelper::getValue(Invite::$L_WHO_CHOOSER, $model->who_chooser);},
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'who_chooser',
					'data' => Invite::$L_WHO_CHOOSER,
					'pluginOptions' => ['allowClear' => false,],
					'options' => ['multiple' => true],
				]),
				'headerOptions' => [
					'style' => 'min-width: 220px;'
				]
			],
			[
				'attribute' => 'who_buyer',
				'label' => 'Кто покупает',
				'value' => function(Invite $model){return ArrayHelper::getValue(Invite::$L_WHO_BUYER, $model->who_buyer);},
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'who_buyer',
					'data' => Invite::$L_WHO_BUYER,
					'pluginOptions' => ['allowClear' => false,],
					'options' => ['multiple' => true],
				]),
				'headerOptions' => [
					'style' => 'min-width: 220px;'
				]
			],
			[
				'attribute' => 'shop_name',
				'label' => 'Магазин ЛМ',
				'headerOptions' => [
					'style' => 'min-width: 100px;'
				]
			],
			[
				'attribute' => 'distance',
				'label' => 'Расстояние до магазина',
				'value' => function(Invite $model){return ArrayHelper::getValue(Invite::$L_DISTANCE, $model->distance);},
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'distance',
					'data' => Invite::$L_DISTANCE,
					'pluginOptions' => ['allowClear' => false,],
					'options' => ['multiple' => true],
				]),
				'headerOptions' => [
					'style' => 'min-width: 80px;'
				]
			],
			[
				'attribute' => 'money',
				'label' => 'Доход',
				'value' => function(Invite $model){return ArrayHelper::getValue(Invite::$L_MONEY, $model->money);},
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'money',
					'data' => Invite::$L_MONEY,
					'pluginOptions' => ['allowClear' => false,],
					'options' => ['multiple' => true],
				]),
				'headerOptions' => [
					'style' => 'min-width: 220px;'
				]
			],
        ],
        'tableOptions' => [
            'class'=>'table table-striped table-bordered',
            'style'=>'max-width: auto; width: auto;',
        ]
    ]); ?>
    <?php Pjax::end(); ?>
</div>

<?php $rowsCount = $dataProvider->pagination->totalCount; ?>
<?= $this->render('__modal_export_to_excel', [
    'buttonId' => 'export-btn',
    'action' => 'invite/export',
    'rowsCount' => $rowsCount,
]) ?>

<?= $this->render('__modal_change_email', ['model'=>null]) ?>
<?php $this->registerJs(
    '$("body").on("click", ".edit-email-btn", function() {
        $("#inviteChangeEmailFormModal").attr("data-inviteChangeEmailForm_invite_id", $(this).attr("data-id"));
        $("#inviteChangeEmailFormModal").attr("data-inviteChangeEmailForm_email", $(this).attr("data-email"));
        $("#inviteChangeEmailForm").attr("method", "GET");
        $("#inviteChangeEmailForm").html("");
        $("#inviteChangeEmailForm").submit();
        $("#inviteChangeEmailFormModal").modal();
        return false;
    });
    
    $.pjax.defaults.timeout = false;    
    ');
?>

<?= $this->render('__modal_change_status', ['model'=>null]) ?>
<?php $this->registerJs(
	'$("body").on("click", ".edit-status-btn", function() {
        $("#inviteChangeStatusFormModal").attr("data-inviteChangeStatusForm_invite_id", $(this).attr("data-id"));
        $("#inviteChangeStatusFormModal").attr("data-inviteChangeStatusForm_status", $(this).attr("data-status"));
        $("#inviteChangeStatusForm").attr("method", "GET");
        $("#inviteChangeStatusForm").html("");
        $("#inviteChangeStatusForm").submit();
        $("#inviteChangeStatusFormModal").modal();
        return false;
    });
    
    $.pjax.defaults.timeout = false;    
    ');
?>