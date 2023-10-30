<?php

use common\models\User;
use yii\grid\GridView;
use yii\web\View;

/* @var $this yii\web\View */

?>

<div id="familiesSelectModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Семьи</h4>
            </div>
            <div class="modal-body">
                <?php \yii\widgets\Pjax::begin(['enablePushState'=>false, 'id'=>'families-select-grid-pjax', 'timeout' => false, ]);?>
                <?php
                $searchModel = new \frontend\models\user\FamilySearch();
                $dataProvider = $searchModel->search(User::className(), Yii::$app->request->queryParams, ['pagination' => ['pageSize' => 10, 'defaultPageSize' => 10,],]);
                ?>
                <?= GridView::widget([
                    'id' => 'families-select-grid',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'multiple' => true,

                        ],
                        'fio',
                        'username',
                        [
                            'format' => 'html',
                            'header' => 'Город',
                            'value' => function(User $model) {return implode(',<br/>', \yii\helpers\ArrayHelper::getColumn($model->cities, 'name'));},
                            'filter' => \kartik\widgets\Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'city_id',
                                'data' => \common\models\City::getList(),
                                'pluginOptions' => ['allowClear' => false,],
                                'options' => ['multiple' => true],
                            ]),
                            'headerOptions' => [
                                'style' => 'width: 180px;'
                            ],
                            'contentOptions' => [
                                'class' => 'table-family-content'
                            ],
                        ],
						[
							'format' => 'html',
							'header' => 'Объект ремонта',
							'value' => function(User $model) {return implode(',<br/>', \yii\helpers\ArrayHelper::getColumn($model->repairObjects, 'name'));},
							'filter' => \kartik\widgets\Select2::widget([
								'model' => $searchModel,
								'attribute' => 'object_repair_id',
								'data' => \common\models\ObjectRepair::getList(),
								'pluginOptions' => ['allowClear' => false,],
								'options' => ['multiple' => true],
							]),
							'headerOptions' => [
								'style' => 'width: 180px;'
							],
                            'contentOptions' => [
                                'class' => 'table-family-content'
                            ],
						],
                        [
                            'format'        => 'html',
                            'header'        => 'Куратор',
                            'value'         => function (User $model) {
                                if (!empty($model->curator->fio)) {
                                    return $model->curator->fio;
                                } else {
                                    return '';
                                }
                            },
                            'filter'        => \kartik\widgets\Select2::widget([
                                'model'         => $searchModel,
                                'attribute'     => 'curator_id',
                                'data'          => \common\models\User::getCuratorsList(),
                                'pluginOptions' => ['allowClear' => false,],
                                'options'       => ['multiple' => true],
                            ]),
                            'headerOptions' => [
                                'style' => 'width: 180px;',
                            ],
                            'contentOptions' => [
                                'class' => 'table-family-content'
                            ],
                        ],
                        [
                            'format'        => 'html',
                            'header'        => 'Помещения ремонта',
                            'value'         => function (User $model) {
                                return implode(',<br/>',
                                    \yii\helpers\ArrayHelper::getColumn($model->repairRooms, 'name'));
                            },
                            'filter'        => \kartik\widgets\Select2::widget([
                                'model'         => $searchModel,
                                'attribute'     => 'room_repair_id',
                                'data'          => \common\models\RoomRepair::getList(),
                                'pluginOptions' => ['allowClear' => false,],
                                'options'       => ['multiple' => true],
                            ]),
                            'headerOptions' => [
                                'style' => 'width: 180px;',
                            ],
                            'contentOptions' => [
                                'class' => 'table-family-content'
                            ],
                        ],
                        [
                            'format'        => 'html',
                            'header'        => 'Планируемые работы',
                            'value'         => function (User $model) {
                                return implode(',<br/>',
                                    \yii\helpers\ArrayHelper::getColumn($model->repairWorks, 'name'));
                            },
                            'filter'        => \kartik\widgets\Select2::widget([
                                'model'         => $searchModel,
                                'attribute'     => 'work_repair_id',
                                'data'          => \common\models\WorkRepair::getList(),
                                'pluginOptions' => ['allowClear' => false,],
                                'options'       => ['multiple' => true],
                            ]),
                            'headerOptions' => [
                                'style' => 'width: 180px;',
                            ],
                            'contentOptions' => [
                                'class' => 'table-family-content'
                            ],
                        ],
                        [
                            'format'        => 'html',
                            'header'        => 'Магазины покупки',
                            'value'         => function (User $model) {
                                return implode(',<br/>',
                                    $model
                                        ->getJournalShops()
                                        ->distinct()
                                        ->getShopName()
                                        ->asArray()
                                        ->column()
                                );
                            },
                            'filter'        => \kartik\widgets\Select2::widget([
                                'model'         => $searchModel,
                                'attribute'     => 'goods_shop_id',
                                'data'          => \common\models\GoodsShop::getList(),
                                'pluginOptions' => ['allowClear' => false,],
                                'options'       => ['multiple' => true],
                            ]),
                            'headerOptions' => [
                                'style' => 'width: 180px;',
                            ],
                            'contentOptions' => [
                                'class' => 'table-family-content'
                            ],
                        ],
                    ],
                ]); ?>
                <?php \yii\widgets\Pjax::end(); ?>
            </div>
            <div class="modal-footer">
                <?= \yii\bootstrap\Html::button('Выбрать все', ['class'=>'btn btn-primary select-all-btn', 'data-dismiss'=>'modal', 'title' => 'Выбрать все отфильтрованные семьи со всех страниц'])?>
                <?= \yii\bootstrap\Html::button('Ок', ['class'=>'btn btn-primary save-btn', 'data-dismiss'=>'modal',])?>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJsFile('@web/js/families-select-helper.min.js?v=1712051148', ['position' => View::POS_END, 'depends'=>\yii\web\JqueryAsset::className()])?>
<?php $this->registerJs(
<<<JS
var familiesSelectHelper = new FamiliesSelectHelper('#families-select-grid-pjax', '#families-select-grid', '#task-form', '#families-selected-grid-pjax');
JS
);?>
