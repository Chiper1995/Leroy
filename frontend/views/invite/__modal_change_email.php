<?php

/* @var $this yii\web\View */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

if ($model == null)
    $model = new \frontend\models\InviteChangeEmailForm();
?>

<div id="inviteChangeEmailFormModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php \yii\widgets\Pjax::begin([
                'enablePushState'=>false, 'id'=>'inviteChangeEmailFormPjax', 'timeout' => false,
                'clientOptions'=>[
                    'pjax:clicked'=>new \yii\web\JsExpression('function(options){}')
                ]
            ]);?>
            <?php $form = ActiveForm::begin(['action'=>\yii\helpers\Url::to(['invite/update-email']), 'id'=>'inviteChangeEmailForm', 'enableAjaxValidation' => false, 'options'=>['data-pjax' => '0']]); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Редактирование email</h4>
            </div>
            <div class="modal-body">
                <?= $form->field($model, 'email')->textInput(['type' => 'text', 'min'=>'1', 'id'=>'inviteChangeEmailForm_email']) ?>
                <?= Html::activeHiddenInput($model, 'invite_id', ['id'=>'inviteChangeEmailForm_invite_id']) ?>
            </div>
            <div class="modal-footer">
                <?= \yii\bootstrap\Html::submitButton('Ок', ['class'=>'btn btn-primary save-btn',])?>
                <?= \yii\bootstrap\Html::button('Отмена', ['class'=>'btn btn-default cancel-btn', 'data-dismiss'=>'modal',])?>
            </div>
            <?php ActiveForm::end(); ?>

            <?php $this->registerJs('
            	$("#inviteChangeEmailForm_invite_id").val($("#inviteChangeEmailFormModal").attr("data-inviteChangeEmailForm_invite_id"));
            	$("#inviteChangeEmailForm_email").val($("#inviteChangeEmailFormModal").attr("data-inviteChangeEmailForm_email"));
			');?>
            <?php if ($model->saved) $this->registerJs('$("#inviteChangeEmailFormModal").modal("hide"); $.pjax.reload({container:"#invite-grid-pjax"});');?>
            <?php \yii\widgets\Pjax::end(); ?>
        </div>
    </div>
</div>