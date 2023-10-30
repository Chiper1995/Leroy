<?php

use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

$class = explode("\\", $searchModel::className());
$class = array_pop($class);
?>

    <div class="col-md-12 container <?= $class ?>" style="padding-bottom: 20px;">
        <div class="feed-filter-form row">
            <?php $form = ActiveForm::begin(['method' => 'get', 'id' => 'filter-for-feed', 'action' => empty($searchModel->typeFilter) ? '/' : '/journal/smart-search']); ?>

            <div class="col-md-3 pull-right">
                <b>Работы:</b>
                <?= Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'workRepair',
                    'data' => \common\models\WorkRepair::getList(),
                    'pluginOptions' => ['allowClear' => true],
                    'options' => [
                        'multiple' => false,
                        'placeholder' => 'Фильтр по работам',
                        'id' => 'work-repair-feed',
                    ],
                ]); ?>
            </div>
            <div class="col-md-3 pull-right">
                <b>Тип поста:</b>
                <?= Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'type',
                    'data' => \common\models\JournalType::getList(),
                    'pluginOptions' => ['allowClear' => true],
                    'options' => [
                        'multiple' => false,
                        'placeholder' => 'Выбери тип поста',
                        'id' => 'type-feed',
                    ],
                ]); ?>
            </div>
            <div class="col-md-3 pull-right">
                <b>Помещение:</b>
                <?= Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'roomRepair',
                    'data' => \common\models\RoomRepair::getList(),
                    'pluginOptions' => ['allowClear' => true],
                    'options' => [
                        'multiple' => false,
                        'placeholder' => 'Выбор помещения ремонта',
                        'id' => 'room-repair-feed',
                    ],
                ]); ?>
            </div>
            <div class="col-md-3 pull-right">
                <b>Города:</b>
                <?= Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'city',
                    'data' => \common\models\City::getList(),
                    'pluginOptions' => ['allowClear' => true],
                    'options' => [
                        'multiple' => false,
                        'placeholder' => 'Фильтр по городам',
                        'id' => 'city-feed',
                    ],
                ]); ?>
            </div>
            <?php
            /*
            @todo убрать комменты после успешного тестирования
            */
            /*
                = $form->field($searchModel, 'typeFilter')
                    ->hiddenInput(['value' => 'userJournals'])
                    ->label(false);
            */
            ?>
            <input type="hidden" class="form-control" name="<?= $class . '[typeFilter]' ?>" value="userJournals">
            <?php ActiveForm::end(); ?>
        </div>
    </div>

<?php $this->registerJs(
    '(function(){' .
    '   var submitTimeout = undefined; ' .
    '   $("#work-repair-feed").add("#type-feed").add("#city-feed").add("#room-repair-feed").on("change", function(){' .
    '       if (submitTimeout != undefined) {clearTimeout(submitTimeout); submitTimeout = undefined;} ' .
    '       submitTimeout = setTimeout(function(){$("#filter-for-feed").submit();}, 1500); ' .
    '   });' .
    '})();'
); ?>
<?php $this->registerJs(<<<'JS'
    jQuery(document).ready(function () {
        $('#filter-for-feed').on('submit', function(e){
            if($('#alljournalsmartsearch-smartsearch').val().length > 0)
                $(this).append('<input type="hidden" name="AllJournalSmartSearch[smartSearch]" value="'+$('#alljournalsmartsearch-smartsearch').val()+'">');
	  	});
    });
JS
); ?>