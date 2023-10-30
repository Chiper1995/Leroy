<?php

use common\models\User;
use common\rbac\Rights;
use yii\bootstrap\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Task */

?>

<div class="row">
    <div class="col-md-12">

    </div>
</div>
<div class="row">
    <div class="col-md-12 content-container" style="margin-bottom: 15px;" id="content-container-table-family">
        <span id="loading-table-family"></span>
        <div class="content-container-caption">
            <h2>Семьи</h2>
        </div>
        <div>
            <?php \yii\widgets\Pjax::begin(['enablePushState' => false, 'id' => 'families-selected-grid-pjax', 'timeout' => false, 'linkSelector' => false, 'formSelector' => false,]); ?>
            <?php
            $idList = \yii\helpers\ArrayHelper::getColumn($model->families, function ($el) {
                return $el->user_id;
            });
            $searchModel = new \frontend\models\user\FamilySearchForTask();
            $dataProvider = $searchModel->search($idList, User::className(), Yii::$app->request->post(), ['pagination' => ['pageSize' => 10, 'defaultPageSize' => 10,],]);
            ?>
            <?= GridView::widget([
                'id' => 'families-selected-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'emptyText' => 'Семьи не выбраны',
                'columns' => [
                    'fio',
                    [
                        'format' => 'html',
                        'header' => 'Город',
                        'value' => function (User $model) {
                            return implode(',<br/>', \yii\helpers\ArrayHelper::getColumn($model->cities, 'name'));
                        },
                        'filter' => \kartik\widgets\Select2::widget([
                            'model' => $searchModel,
                            'attribute' => 'city_id',
                            'data' => \common\models\City::getList(),
                            'pluginOptions' => ['allowClear' => false,],
                            'options' => ['multiple' => true],
                        ]),
                        'headerOptions' => [
                            'style' => 'width: 40%;'
                        ]
                    ],
                ],
            ]); ?>

            <?php foreach ($model->families as $family): ?>
                <?= Html::hiddenInput('Task[families][][user_id]', $family->user_id, ['class' => 'family-' . $family->user_id]) ?>
            <?php endforeach; ?>
            <?php \yii\widgets\Pjax::end(); ?>
        </div>
        <div class="text-center">
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-users']) . '&nbsp;' . 'Добавить семьи', '#', ['class' => 'btn btn-primary', 'data-toggle' => 'modal', 'data-target' => '#familiesSelectModal']) ?>
        </div>
    </div>
</div>

<?= $this->render('__modal_families_select', []) ?>

<?php $this->registerJs(
    <<<JS
$("body").on('beforeFilter', '#families-selected-grid', function(event) {
    var frm = $('#task-form');
    event = {currentTarget: frm[0], preventDefault: function(){}};
    $.pjax.submit(event, $('#families-selected-grid-pjax'), {"push":false,"replace":false,"timeout":false,"scrollTo":false});
    return false;
});

$("#families-selected-grid-pjax").on('click', '#families-selected-grid a', function(event) {
    var frm = $('#task-form');
    frm.attr('action', $(this).attr('href'));
    event = {currentTarget: frm[0], preventDefault: function(){}};
    $.pjax.submit(event, $('#families-selected-grid-pjax'), {"push":false,"replace":false,"timeout":false,"scrollTo":false});
    return false;
});

$("body").on('click', '#familiesSelectModal .select-all-btn, #familiesSelectModal .save-btn', function(event) {
    $('#task-form button[name="save"]').prop("disabled", true);
    $('#content-container-table-family a.btn').hide();
    $('#content-container-table-family > div').addClass("table-family-opacity");
    $('#loading-table-family').show();
});

$(document).on('pjax:end', function() {
    $('#task-form button[name="save"], #content-container-table-family a.btn').prop("disabled", false);
    $('#content-container-table-family a.btn').show();
    $('#content-container-table-family > div').removeClass("table-family-opacity");
    $('#loading-table-family').hide();
})
JS
); ?>


