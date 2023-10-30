<?php

use common\models\Help;
use dosamigos\ckeditor\CKEditor;
use kartik\widgets\Select2;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\Help */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div>

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'default', [])->widget(Select2::classname(), ['data' => Help::getDefaultList(), 'hideSearch' => true]) ?>
    <?php echo $form->field($model, 'content')->widget(CKEditor::className(), [
        'options' => ['rows' => 6,],
        'clientOptions'=>['height'=>600],
        'preset' => 'full'
    ]) ?>

    <?php echo $form->field($model, 'roles', [])->widget(Select2::classname(), [
        'data' => User::getRoleList(),
        'pluginOptions' => ['allowClear' => false,],
        'options' => [
            'multiple' => true,
            'placeholder' => 'Все'
        ],
    ]) ?>

    <div class="form-group">
        <?php echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-with-margin-right']) ?>
        <?php echo Html::submitButton('Отмена', ['class' => 'btn btn-default', 'name' => 'cancel', 'formnovalidate' => '1',]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
