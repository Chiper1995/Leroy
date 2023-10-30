<?php

/* @var $this yii\web\View */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

if ($model == null)
    $model = new \frontend\models\user\FamilyIncreasePointsForm();
?>

<div id="increaseModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php \yii\widgets\Pjax::begin([
                'enablePushState'=>false, 'id'=>'increase-form-pjax', 'timeout' => false,
                'clientOptions'=>[
                    'pjax:clicked'=>new \yii\web\JsExpression('function(options){}')
                ]
            ]);?>
            <?php $form = ActiveForm::begin(['action'=>\yii\helpers\Url::to(['user/increase-points']), 'id'=>'increase-form', 'enableAjaxValidation' => false, 'options'=>['data-pjax' => '0']]); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Списать баллы</h4>
            </div>
            <div class="modal-body">
                <?= $form->field($model, 'points')->textInput(['type' => 'number', 'min'=>'1', 'id'=>'increase-form-points']) ?>
                <?= $form->field($model, 'description')->textarea(['rows' => '3','id'=>'increase-form-description']) ?>
                <?= Html::activeHiddenInput($model, 'family_id', ['id'=>'increase-form-family-id']) ?>
            </div>
            <div class="modal-footer">
                <?= \yii\bootstrap\Html::submitButton('Ок', ['class'=>'btn btn-primary save-btn',])?>
                <?= \yii\bootstrap\Html::button('Отмена', ['class'=>'btn btn-default cancel-btn', 'data-dismiss'=>'modal',])?>
            </div>
            <?php ActiveForm::end(); ?>

            <?php $this->registerJs('$("#increase-form-family-id").val($("#increaseModal").attr("data-family-id"))');?>
            <?php if ($model->saved) $this->registerJs('$("#increaseModal").modal("hide"); $.pjax.reload({container:"#family-grid-pjax"});');?>
            <?php \yii\widgets\Pjax::end(); ?>
        </div>
    </div>
</div>