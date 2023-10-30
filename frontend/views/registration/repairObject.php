<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\registration\RepairObject */

use kartik\widgets\Select2;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;

$this->context->layout = "login";

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $this->registerJs("
    $('#form-registration-repair-objects').on('ajaxBeforeSend', function (event, jqXHR, settings) {
        $('.site-login').addClass('loading');
    }).on('ajaxComplete', function (event, jqXHR, textStatus) {
        $('.site-login').removeClass('loading');
    });
    "
);?>

<div class="signup-block">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="site-login">
        <div class="row">
            <div class="col-md-12">
                <?php $form = ActiveForm::begin([
                    'id' => 'form-registration-repair-objects',
                    'fieldConfig' => ['template'=>"<span class='input'>{input}\n{label}</span>\n{error}",],
                ]); ?>

                <div class="row">
                    <div class="col-md-12 text-center" style="margin-bottom: 10px;">
                        Для продолжения регистрации, заполните форму ниже
                    </div>
                </div>
                
                <?= $form->field($model, 'object_repair_list', ['template'=>"{label}\n{input}\n{error}",])
                    ->checkboxList(\common\models\ObjectRepair::getList(), [])?>

                <?= $form->field($model, 'room_repair_list', ['template'=>"{label}\n{input}\n{error}",])
                        ->checkboxList(\common\models\RoomRepair::getList(), [])?>

                <div class="form-group text-center form-buttons">
                    <?= Html::submitButton('Далее &rarr;', ['class' => 'btn btn-primary', 'name' => 'next', 'style' => 'width: 180px;']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>