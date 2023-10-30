<?php

use execut\widget\TreeView;
use frontend\helpers\GoodsTreeDataHelper;

?>

<div id="goodsSelectModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Товары</h4>
            </div>
            <div class="modal-body">
                <?=
                TreeView::widget([
                    'id' => 'GoodsTreeView',
                    'data' => GoodsTreeDataHelper::getAll(),
                    'size' => TreeView::SIZE_SMALL,
                    'header' => 'Выбери товары',
                    'searchOptions' => [
                        'inputOptions' => [
                            'placeholder' => 'Поиск по названию...'
                        ],
                    ],
                    'clientOptions' => [
                        'levels' => 1,
                        'multiSelect' => true,
                        'onNodeSelected' => new \yii\web\JsExpression('journalGoods.onNodeSelected'),
                        'onNodeUnselected' => new \yii\web\JsExpression('journalGoods.onNodeUnselected'),
                        'selectedBackColor' => '#fff',
                        'borderColor' => '#fff',
                        'selectedIcon' => 'glyphicon glyphicon-ok',
                    ],
                ]);
                ?>
            </div>
            <div class="modal-footer">
                <?= \yii\bootstrap\Html::button('Ок', ['class'=>'btn btn-primary save-btn', 'data-dismiss'=>'modal', 'style' => 'max-width: 160px;'])?>
                <?//= \yii\bootstrap\Html::button('Сохранить', ['class'=>'btn btn-primary save-btn', 'data-dismiss'=>'modal', 'style' => 'max-width: 160px;'])?>
                <?//= //\yii\bootstrap\Html::button('Закрыть', ['class'=>'btn btn-default cancel-btn', 'data-dismiss'=>'modal', 'style' => 'max-width: 160px;'])?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->