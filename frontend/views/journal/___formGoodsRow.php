<?php
use common\models\GoodsShop;
use common\models\JournalGoods;
use kartik\widgets\Select2;
use yii\bootstrap\Html;

/**@var integer $goodsIndex*/
/**@var JournalGoods $goods*/
?>

<div class="row goods<?php if ($goodsIndex == -1):?>-template<?php endif;?> goods-<?= $goodsIndex ?>" data-id="<?=($goodsIndex == -1) ? '' : $goods->goods_id?>">
    <div class="data-column container-fluid">
        <div class="row">
            <div class="col-md-4 goods-name">
                <span><?= ($goodsIndex == -1) ? '' : $goods->goods->name ?></span>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-3<?=(($goodsIndex != -1)and($goods->hasErrors('quantity'))) ? ' has-error' : ''?>">
                        <?= Html::input('number', ($goodsIndex == -1) ? 'quantity' : 'Journal[goods]['.$goodsIndex.'][quantity]', ($goodsIndex == -1) ? '' : doubleval($goods->quantity), ['min'=>'1', 'class'=>'form-control goods-quantity'])?>
                    </div>
                    <div class="col-md-3<?=(($goodsIndex != -1)and($goods->hasErrors('price'))) ? ' has-error' : ''?>">
                        <?= Html::input('text', ($goodsIndex == -1) ? 'price' : 'Journal[goods]['.$goodsIndex.'][price]', ($goodsIndex == -1) ? '' : Yii::$app->formatter->format(doubleval($goods->price), ['decimal', 2]), ['min'=>'1', 'class'=>'form-control goods-price'])?>
                    </div>
                    <div class="col-md-3 goods-sum">
                        <span><?=($goodsIndex == -1) ? '' : Yii::$app->formatter->format(doubleval($goods->quantity*$goods->price), ['decimal', 2])?></span>
                    </div>
                    <div class="col-md-3">
                        <?= Html::checkbox(($goodsIndex == -1) ? 'online' : 'Journal[goods]['.$goodsIndex.'][online]', ($goodsIndex == -1) ? 0 : $goods->online , ['min'=>'1', 'class'=>'goods-online','label' => '<i class="fa fa-shopping-cart"></i> <i class="fa fa-credit-card"></i>'])?>
                    </div>
                </div>
            </div>
            <div class="col-md-4<?=(($goodsIndex != -1)and($goods->hasErrors('goods_shop_id'))) ? ' has-error' : ''?>">
                <?= ($goodsIndex == -1)
                    ? Html::dropDownList('goods_shop_id', null, GoodsShop::getList(), ['class'=>'form-control goods-shop-id'])
                    : Select2::widget(['name' => 'Journal[goods]['.$goodsIndex.'][goods_shop_id]', 'value' => $goods->goods_shop_id, 'data' => GoodsShop::getList(), 'options'=>['id'=>'goods-shop-id-'.$goodsIndex,'class'=>'goods-shop-id', 'placeholder'=>'']]) ?>
            </div>
        </div>
        <?php if (($goodsIndex != -1)and($goods->hasErrors())):?>
        <div class="row">
            <div class="col-md-12" style="margin-top: 6px;">
                <?= Html::errorSummary($goods, ['header'=>'', 'class'=>'bg-danger text-danger']);?>
            </div>
        </div>
        <?php endif;?>
    </div>
    <div class="buttons-column text-right">
        <div class="row">
            <div class="col-md-12 goods-duplicate-block">
                <a class="goods-duplicate" href="#" title="Дублировать" data-toggle="tooltip" data-placement="bottom"><i class="glyphicon glyphicon-duplicate"></i></a>
            </div>
            <div class="col-md-12 goods-delete-block">
                <a class="goods-delete" href="#" title="Удалить" data-toggle="tooltip" data-placement="bottom"><i class="glyphicon glyphicon-trash"></i></a>
            </div>
        </div>
    </div>
    <?= Html::hiddenInput(($goodsIndex == -1) ? 'goods_id' : 'Journal[goods]['.$goodsIndex.'][goods_id]', ($goodsIndex == -1) ? '' : $goods->goods_id, ['class'=>'goods-id']) ?>
</div>