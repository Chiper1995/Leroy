<?php
use execut\widget\TreeView;
use frontend\helpers\GoodsTreeDataHelper;

/** @var \yii\web\View $this */
/** @var integer $id */
/** @var string $onNodeSelected */
/** @var string $onNodeUnselected */

?>

<div id="<?=$id?>" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Товары</h4>
            </div>
            <div class="modal-body">
                <?=
                TreeView::widget([
                    'id' => $id.'_TreeView',
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
                        'onNodeSelected' => $onNodeSelected != null ? new \yii\web\JsExpression($onNodeSelected) : null,
                        'onNodeUnselected' => $onNodeUnselected != null ?  new \yii\web\JsExpression($onNodeUnselected) : null,
                        'selectedBackColor' => '#fff',
                        'borderColor' => '#fff',
                    ],
                ]);
                ?>
            </div>
            <div class="modal-footer">
                <?= \yii\bootstrap\Html::button('Ок', ['class'=>'btn btn-primary save-btn', 'data-dismiss'=>'modal', 'style' => 'max-width: 160px;'])?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->