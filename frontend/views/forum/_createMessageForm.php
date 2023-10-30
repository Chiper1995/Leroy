<?php

use dosamigos\ckeditor\CKEditor;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ForumMessage */
/* @var $theme common\models\ForumTheme */
/* @var $form yii\bootstrap\ActiveForm */
?>


<div class="row">
    <div class="col-md-12" style="padding-bottom: 0; margin-bottom: 15px;">
        <div class="forum-create-message-container">
            <div class="container-fluid">
                <h1 id="create-message">Новое сообщение</h1>
                <?php $form = ActiveForm::begin(['enableClientValidation'=>false, 'enableAjaxValidation'=>false, 'id'=>'forum-create-messages-theme-form', 'action'=>\yii\helpers\Url::current(['#'=>'create-message'])]); ?>
                <div class="row">
                    <div class="col-md-12 col-sm-12" style="padding-bottom: 0; margin-bottom: 0;">
                        <?= $form->field($model, 'message')->widget(CKEditor::className(), [
                            'options' => ['rows' => 6],
                            'preset' => 'full'
                        ]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" style="padding-bottom: 0;">
                        <div class="form-group">
                            <?= Html::submitButton('Опубликовать', ['class' => 'btn btn-primary btn-with-margin-right', 'name' => 'save',]) ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>


