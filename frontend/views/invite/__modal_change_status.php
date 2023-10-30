<?php
use frontend\models\InviteChangeStatusForm;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model InviteChangeStatusForm */

if ($model == null)
    $model = new InviteChangeStatusForm();
?>

<div id="inviteChangeStatusFormModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php \yii\widgets\Pjax::begin([
                'enablePushState'=>false, 'id'=>'inviteChangeStatusFormPjax', 'timeout' => false,
                'clientOptions'=>[
                    'pjax:clicked'=>new \yii\web\JsExpression('function(options){}')
                ]
            ]);?>
			<?php $form = ActiveForm::begin([
				'action' => \yii\helpers\Url::to(['invite/update-status']),
				'id' => 'inviteChangeStatusForm',
				'enableAjaxValidation' => false,
				'options' => ['data-pjax' => '0']
			]);
			?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Редактировать статус</h4>
            </div>
            <div class="modal-body">
                <?= $form->field($model, 'status', [])->widget(\kartik\widgets\Select2::className(), [
                    'model' => $model,
                    'attribute' => 'status',
                    'data' => \common\models\Invite::$STATUS_LIST,
                    'pluginOptions' => ['dropdownParent' => new \yii\web\JsExpression('document.getElementById("inviteChangeStatusFormModal")')],
                    'options' => ['id'=>'inviteChangeStatusForm_status'],
                ]) ?>
				<?= Html::activeHiddenInput($model, 'invite_id', ['id'=>'inviteChangeStatusForm_invite_id']) ?>
            </div>
            <div class="modal-footer">
                <?= \yii\bootstrap\Html::submitButton('Ок', ['class'=>'btn btn-primary save-btn',])?>
                <?= \yii\bootstrap\Html::button('Отмена', ['class'=>'btn btn-default cancel-btn', 'data-dismiss'=>'modal',])?>
            </div>
            <?php ActiveForm::end(); ?>

            <?php $this->registerJs('
            	$("#inviteChangeStatusForm_invite_id").val($("#inviteChangeStatusFormModal").attr("data-inviteChangeStatusForm_invite_id"));
            	$("#inviteChangeStatusForm_status").val($("#inviteChangeStatusFormModal").attr("data-inviteChangeStatusForm_status")).change();
            ');?>
            <?php if ($model->saved) $this->registerJs('$("#inviteChangeStatusFormModal").modal("hide"); $.pjax.reload({container:"#invite-grid-pjax"});');?>
            <?php \yii\widgets\Pjax::end(); ?>
        </div>
    </div>
</div>