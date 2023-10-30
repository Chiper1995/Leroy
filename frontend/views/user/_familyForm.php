<?php

use kartik\widgets\Select2;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="city-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'role', [])->widget(Select2::classname(), ['data' => \common\models\User::getUserRoleList(),]) ?>

    <?php if ($model->isNewRecord):?>
        <?= $this->render("_formSetPassword", ['form'=>$form, 'model'=>$model])?>
    <?php endif;?>

    <?= $form->field($model, 'fio')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'city_id', [])->widget(Select2::classname(), ['data' => \common\models\City::getList(),]) ?>
    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?php if (!$model->isNewRecord):?>
        <?= \yii\bootstrap\Collapse::widget([
            'items' => [
                [
                    'label' => 'Сменить пароль',
                    'content' => $this->render("_formSetPassword", ['form'=>$form, 'model'=>$model])
                ]
            ]
        ])?>
    <?php endif;?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-with-margin-right']) ?>
        <?= Html::submitButton('Отмена', ['class' => 'btn btn-default', 'name' => 'cancel', 'formnovalidate' => '1',]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
