<?php

use common\models\Goods;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\journal\AllJournalSmartSearch */

$items = Goods::findAll($searchModel->goods_filter);
?>

<?php $this->registerJsFile('@web/js/all-journals-goods.min.js', ['position' => View::POS_END, 'depends' => [\yii\web\JqueryAsset::className()]]) ?>

<?php $this->registerJs('var allJournalsGoods;', View::POS_HEAD) ?>
<?php $this->registerJs('allJournalsGoods = new AllJournalsGoods("all-journal-goods", "allJournalsGoodsSelectModal_TreeView", "allJournalsGoodsSelectModal", "allJournalsGoodsFilterForm", "AllJournalSmartSearch");') ?>

<?= \frontend\widgets\ModalGoodsSelect\ModalGoodsSelect::widget([
    'id' => 'allJournalsGoodsSelectModal',
    'onNodeSelected' => 'allJournalsGoods.onNodeSelected',
    'onNodeUnselected' => 'allJournalsGoods.onNodeUnselected',
]) ?>

<?php $form = ActiveForm::begin(['id' => 'allJournalsGoodsFilterForm', 'method' => 'GET', 'action' => '/journal/smart-search']); ?>
<?php $selectGoodsJs = null; ?>
<?php if (Yii::$app->user->can(\common\rbac\Rights::FILTER_JOURNALS_BY_GOODS)): ?>
    <div class="row" style="padding-top: 5px;">
        <div class="col-md-6">
            <div class="form-group">
                <div id="all-journal-goods" class="all-journal-goods">
                    <div class="well well-sm" style="background-color: white">
                        <span class="prompt"<?= ((count($items) > 0) ? 'style="display:none;"' : '') ?>>Фильтр по товарам</span>
                        <ul>
                            <?php $selectGoodsJs = ""; ?>
                            <?php foreach ($items as $goods): ?>
                                <li data-id="<?= $goods->id ?>"><span
                                            class="goods-delete">&times;</span><?= $goods->name ?>
                                    <input type="hidden" value="<?= $goods->id ?>"
                                           name="AllJournalSmartSearch[goods_filter][]"></li>
                                </li>
                                <?php $selectGoodsJs .= '$("#allJournalsGoodsSelectModal_TreeView").treeview("selectNode", [allJournalsGoods.getNodeById(' . $goods->id . '), {silent: true}]);'; ?>
                            <?php endforeach; ?>
                        </ul>
                        <?= Html::a('...', '#',
                            [
                                'title' => 'Выбрать товары',
                                'class' => 'btn btn-primary btn-xs open-all-journal-goods-select-btn',
                                'data' => [
                                    'pjax' => '0', 'toggle' => 'modal', 'placement' => 'bottom', 'target' => '#allJournalsGoodsSelectModal',
                                ],
                            ]
                        ); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <?= \kartik\widgets\Select2::widget([
                'model' => $searchModel,
                'attribute' => 'repairWorks_filter',
                'data' => \common\models\WorkRepair::getList(),
                'pluginOptions' => ['allowClear' => false,],
                'options' => ['multiple' => true, 'placeholder' => 'Фильтр по работам', 'id' => 'repair-works-filter'],
            ]) ?>

            <?php $this->registerJs(
                '(function(){' .
                '   var submitTimeout = undefined; ' .
                '   $("#repair-works-filter").on("change", function(){' .
                '       if (submitTimeout != undefined) {clearTimeout(submitTimeout); submitTimeout = undefined;} ' .
                '       submitTimeout = setTimeout(function(){$("#allJournalsGoodsFilterForm").submit();}, 1500); ' .
                '   });' .
                '})();'
            ); ?>
        </div>
    </div>
<?php endif; ?>
<?= $form->field($searchModel, 'typeFilter')
    ->hiddenInput(['value' => 'allJournals'])
    ->label(false);
?>
<?php ActiveForm::end(); ?>
<?php if ($selectGoodsJs !== null) $this->registerJs($selectGoodsJs); ?>
<?php $this->registerJs(<<<'JS'
    jQuery(document).ready(function () {
        $('#allJournalsGoodsFilterForm').on('submit', function(e){
            if($('#alljournalsmartsearch-smartsearch').val().length > 0)
                $(this).append('<input type="hidden" name="AllJournalSmartSearch[smartSearch]" value="'+$('#alljournalsmartsearch-smartsearch').val()+'">');
	  	});
    });
JS
); ?>
