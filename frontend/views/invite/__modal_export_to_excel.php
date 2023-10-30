<?php
use yii\bootstrap\Html;

/* @var yii\web\View $this */
/* @var string $buttonId */
/* @var string $action */
/* @var integer $rowsCount */

$rowsPerLink = 3000;
?>

<div id="<?= $buttonId ?>-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Экспорт в Excel: выбор диапазона</h4>
            </div>
            <div class="modal-body">
                <ul class="list-unstyled">
                <?php
                $linkCount = ceil($rowsCount/$rowsPerLink);
                $links = [];
                for($i=0;$i<$linkCount;$i++) {
                    $links[] = [$i*$rowsPerLink+1, min(($i+1)*$rowsPerLink, $rowsCount)];
                }
                ?>
                <?php for($i=count($links)-1;$i>=0;$i--):?>
                    <li><?=
                        Html::a(
                            Html::icon('download') . '&nbsp;&nbsp;Записи с ' . $links[$i][0] . ' по ' . $links[$i][1],
                            [$action, 'limit' => $rowsPerLink, 'offset' => $links[$i][0]],
                            ['target', '_blank']
                        )?>
                    </li>
                <?php endfor;?>
                </ul>
            </div>
            <div class="modal-footer">
                <?= Html::button('Отмена', ['class'=>'btn btn-default cancel-btn', 'data-dismiss'=>'modal',])?>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs(
    '$("body").on("click", "#' . $buttonId . '", function() {
        if (' . $rowsCount . '<' . $rowsPerLink . ') {
            return true;
        }
        
        $("#' . $buttonId . '-modal").modal();
        return false;
    });');
?>