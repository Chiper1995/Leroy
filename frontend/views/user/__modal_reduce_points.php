<?php
use frontend\models\user\FamilyReducePointsForm;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model FamilyReducePointsForm */

if ($model == null)
    $model = new FamilyReducePointsForm();
?>

<div id="reduceModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php \yii\widgets\Pjax::begin([
                'enablePushState'=>false, 'id'=>'reduce-form-pjax', 'timeout' => false,
                'clientOptions'=>[
                    'pjax:clicked'=>new \yii\web\JsExpression('function(options){}')
                ]
            ]);?>
            <?php $form = ActiveForm::begin(['action'=>\yii\helpers\Url::to(['user/reduce-points']), 'id'=>'reduce-form', 'enableAjaxValidation' => false, 'options'=>['data-pjax' => '0']]); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Списать баллы</h4>
            </div>
            <div class="modal-body">
                <?= $form->field($model, 'points')->textInput(['type' => 'number', 'min'=>'1', 'id'=>'reduce-form-points']) ?>
                <?= $form->field($model, 'description')->textarea(['rows' => '3','id'=>'reduce-form-description']) ?>
                <?= Html::activeHiddenInput($model, 'family_id', ['id'=>'reduce-form-family-id']) ?>
            </div>
            <div class="modal-footer">
                <?= \yii\bootstrap\Html::submitButton('Ок', ['class'=>'btn btn-primary save-btn',])?>
                <?= \yii\bootstrap\Html::button('Отмена', ['class'=>'btn btn-default cancel-btn', 'data-dismiss'=>'modal',])?>
            </div>
            <?php ActiveForm::end(); ?>

            <?php $this->registerJs('$("#reduce-form-family-id").val($("#reduceModal").attr("data-family-id"))');?>
            <?php if ($model->saved) $this->registerJs('$("#reduceModal").modal("hide"); $.pjax.reload({container:"#family-grid-pjax"});');?>
            <?php \yii\widgets\Pjax::end(); ?>
        </div>
    </div>
</div>