<?php

use common\models\Help;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use kartik\widgets\Select2;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\models\Help */
/* @var $form yii\bootstrap\ActiveForm */
?>
<?php
$url = Url::to('presentation/index');
$script = <<< JS
 
        $("#form-presentation").on('beforeSubmit', function (e) {
            e.preventDefault();
            var form_data = new FormData($('form')[0]);
            if($("#tmp-file").val()!=''){
                form_data.set('Help[file]', $("#tmp-file")[0].files[0], $("#tmp-file").val())  
            }
            $.ajax({
                url: location.href,
                dataType: 'html',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                      
                type: 'post',                      
                success: function(response){
                    if(response=='[]')
                        location.href = $url;
                },
                error: function (data) {
                    
                }
             });
            return false;
        });
JS;
$this->registerJs($script);
?>
<?php
$onChangeActivity = <<<JS
 if($("#tmp-file").val()!=''){
    $("#file-presentation").val($("#tmp-file")[0].files[0].name);
   }
JS;
?>
<div>

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'id' => 'form-presentation',
    ]); ?>

    <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'file')->textInput(['id' => 'file-presentation', 'readonly' => true, 'onclick' => '$("#tmp-file").click();']) ?>
    <?php echo $form->field($model, 'content')->widget(CKEditor::className(), [
        'options' => ['rows' => 6,],
        'clientOptions'=>['height'=>600],
        'preset' => 'full'
    ]) ?>
    <?php echo $form->field($model, 'help_id', [])->widget(Select2::classname(), ['data' => \common\models\Help::getList(),]) ?>

    <div class="form-group">
        <?php echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-with-margin-right', 'id' => 'submit-presentation']) ?>
        <?php echo Html::a('Отмена', ['/help/presentation/'], ['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php echo Html::fileInput("tmp-file", '', ['accept' => '.pdf', 'id' => "tmp-file", 'class' => 'hidden', 'onchange' => new \yii\web\JsExpression($onChangeActivity)]) ?>

</div>
