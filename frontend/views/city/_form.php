<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\City */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-with-margin-right']) ?>
        <?= Html::submitButton('Отмена', ['class' => 'btn btn-default', 'name' => 'cancel', 'formnovalidate' => '1',]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
