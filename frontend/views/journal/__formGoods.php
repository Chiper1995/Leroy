<?php
use yii\bootstrap\Html;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\Journal */

?>

<div class="row">
    <div class="col-md-12 col-sm-12 check-container-block">
        <div class="check-container">
            <div class="check-container-inner">
                <div class="check-container-inner-content">
                    <h2>Купленные товары</h2>
                    <div id="journal-goods"<?php if ($model->hasErrors('goods')) echo ' class="has-error"'; ?>>
                        <?= Html::error($model, 'goods', ['tag' => 'p', 'class' => 'help-block help-block-error']); ?>
                        <div class="row goods" <?=(count($model->goods)==0)?'style="display:none;"':''?>>
                            <div class="data-column container-fluid">
                                <div class="row goods-header">
                                    <div class="col-md-4">
                                        Наименование
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-3">
                                                Количество
                                            </div>
                                            <div class="col-md-3">
                                                Цена, <i class="fa fa-rub"></i>
                                            </div>
                                            <div class="col-md-3">
                                                Сумма, <i class="fa fa-rub"></i>
                                            </div>
                                            <div class="col-md-3">
                                                Онлайн
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        Где покупались
                                    </div>
                                </div>
                            </div>
                            <div class="buttons-column text-right">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div style="width:15px;">&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?= $this->render("___formGoodsRow", ['goodsIndex' => -1, 'goods' => null])?>
                        <?php $selectGoodsJs = ""; ?>
                        <?php foreach ($model->goods as $goodsIndex => $goods):?>
                            <?= $this->render("___formGoodsRow", ['goodsIndex' => $goodsIndex, 'goods' => $goods])?>
                            <?php $selectGoodsJs .= '$("#goodsSelectModal_TreeView").treeview("selectNode", [journalGoods.getNodeById('.$goods->goods_id.'), {silent: true}]);';?>
                        <?php endforeach;?>
                    </div>
                    <div class="journal-edit-info">
                        <span class="journal-edit-btn">
                            <?= Html::a(Html::icon('question-sign white').'&nbsp;'.'О разделе', '#', [
                                'class' => 'text-success info-modal-btn', 'data-target'=>'#info-modal-goods',
                                'id' => 'info-modal-goods-btn',
                            ]) ?>
                        </span>
                    </div>
                    <div class="text-center">
                        <?= Html::a(Html::icon('plus').'&nbsp;'.'Добавить товары', '#', ['class' => 'btn btn-primary', 'data-toggle'=>'modal', 'data-target'=>'#goodsSelectModal']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= $this->render('__formInfoGoods') ?>
</div>

<?php \frontend\assets\JournalGoodsAsset::register($this) ?>

<?php $this->registerJs('var journalGoods;', View::POS_HEAD)?>
<?php $this->registerJs('journalGoods = new Goods("journal-goods", "goodsSelectModal_TreeView");')?>

<?= \frontend\widgets\ModalGoodsSelect\ModalGoodsSelect::widget([
    'id'=>'goodsSelectModal',
    'onNodeSelected'=>'journalGoods.onNodeSelected',
    'onNodeUnselected'=>'journalGoods.onNodeUnselected',
]) ?>

<?php $this->registerJs($selectGoodsJs)?>


