<?php

use dosamigos\ckeditor\CKEditor;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model common\models\Task */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="row">
    <div class="col-md-12 content-container" style="padding-bottom: 0; margin-bottom: 15px;">
        <h1><?php echo Html::encode($this->title) ?></h1>
    </div>
</div>

<?php $form = ActiveForm::begin(['enableClientValidation'=>false, 'enableAjaxValidation'=>false, 'id'=>'task-form']); ?>
<div class="row">
    <div class="col-md-12 col-sm-12 content-container" style="padding-bottom: 0; margin-bottom: 15px; min-height: 356px">
        <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?php echo $form->field($model, 'description')->widget(CKEditor::className(), [
            'options' => ['rows' => 6,],
            'clientOptions'=>['height'=>150],
            'preset' => 'full'
        ]) ?>

        <?php echo $form->field($model, 'deadline', ['template' => "{label}\n<div style='max-width:150px;'>{input}</div>\n{hint}\n{error}",])
            ->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE,
                'options' => [
                    'pluginOptions' => [
                        'startDate' => date('d.m.Y'),
                    ],
                ],
            ]) ?>
    </div>
</div>


<?php echo $this->render('__formPhotos', ['model'=>$model, 'form'=>$form]) ?>
<?php echo $this->render('__formFamilies', ['model'=>$model, 'form'=>$form]) ?>

<div class="row">
    <div class="col-md-12 content-container" style="padding-bottom: 0;">
        <div class="form-group">
            <?php echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-with-margin-right', 'name' => 'save',]) ?>
            <?php echo Html::submitButton('Отмена', ['class' => 'btn btn-default', 'name' => 'cancel', 'formnovalidate' => '1',]) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

