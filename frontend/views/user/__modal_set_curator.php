<?php
use frontend\models\user\FamilySetCuratorForm;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model FamilySetCuratorForm */

if ($model == null)
    $model = new FamilySetCuratorForm();
?>

<div id="setCuratorModal" class="modal fade" tabindex="-1" role="dialog">
    <div id="modal-container"></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <?php \yii\widgets\Pjax::begin([
                'enablePushState'=>false, 'id'=>'set-curator-form-pjax', 'timeout' => false,
                'clientOptions'=>[
                    'pjax:clicked'=>new \yii\web\JsExpression('function(options){}')
                ]
            ]);?>
            <?php $form = ActiveForm::begin(['action'=>\yii\helpers\Url::to(['user/set-curator']),
                                             'id'=>'set-curator-form',
                                             'enableAjaxValidation' => false,
                                             'options'=>['data-pjax' => '0']]);
            ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Назначить куратора</h4>
            </div>
            <div class="modal-body">
                <?= $form->field($model, 'curator_id', [])->widget(\kartik\widgets\Select2::className(), [
                    'model' => $model,
                    'attribute' => 'curator_id',
                    'data' => \common\models\User::getCuratorsList(),
                    'pluginOptions' => ['dropdownParent' => new \yii\web\JsExpression('document.getElementById("modal-container")'),],
                    'options' => ['id'=>'set-curator-form-curator-id'],
                ]) ?>
                <?= Html::activeHiddenInput($model, 'family_id', ['id'=>'set-curator-form-family-id']) ?>
            </div>
            <div class="modal-footer">
                <?= \yii\bootstrap\Html::submitButton('Ок', ['class'=>'btn btn-primary save-btn',])?>
                <?= \yii\bootstrap\Html::button('Отмена', ['class'=>'btn btn-default cancel-btn', 'data-dismiss'=>'modal',])?>
            </div>
            <?php ActiveForm::end(); ?>

            <?php $this->registerJs(
                    '$("#set-curator-form-family-id").val($("#setCuratorModal").attr("data-family-id"));' .
                    '$("#set-curator-form-curator-id").val($("#setCuratorModal").attr("data-curator-id")).change();' .
                    '$("#modal-container").offset({ top: 0})'
            );?>
            <?php if ($model->saved) $this->registerJs('$("#setCuratorModal").modal("hide"); $.pjax.reload({container:"#family-grid-pjax"});');?>
            <?php \yii\widgets\Pjax::end(); ?>
        </div>
    </div>
</div>