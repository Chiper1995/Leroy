<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ForumTheme */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="row">
    <div class="col-md-12 content-container" style="padding-bottom: 0; margin-bottom: 15px;">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
</div>

<?php $form = ActiveForm::begin(['enableClientValidation'=>false, 'enableAjaxValidation'=>false, 'id'=>'forum-theme-form']); ?>
<div class="row">
    <div class="col-md-12 col-sm-12 content-container" style="padding-bottom: 0; margin-bottom: 15px;">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12 content-container" style="padding-bottom: 0;">
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-with-margin-right', 'name' => 'save',]) ?>
            <?= Html::submitButton('Отмена', ['class' => 'btn btn-default', 'name' => 'cancel', 'formnovalidate' => '1',]) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

