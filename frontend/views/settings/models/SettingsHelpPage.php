<?php

use dosamigos\ckeditor\CKEditor;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model common\models\settings\SettingsHelpPage */

?>

<div class="settings-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'content')->widget(CKEditor::className(), [
        'options' => ['rows' => 6,],
        'clientOptions'=>['height'=>600],
        'preset' => 'full'
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-with-margin-right']) ?>
        <?= Html::submitButton('Отмена', ['class' => 'btn btn-default', 'name' => 'cancel', 'formnovalidate' => '1',]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

