<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

$this->context->layout = "login";

$this->title = 'Восстановление пароля';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $this->registerJs("
    $('#request-password-reset-form').on('ajaxBeforeSend', function (event, jqXHR, settings) {
        $('.site-login').addClass('loading');
    }).on('ajaxComplete', function (event, jqXHR, textStatus) {
        $('.site-login').removeClass('loading');
    });
    ",
    View::POS_END
);?>

<div class="signup-block">
    <h1>ВОССТАНОВЛЕНИЕ ПАРОЛЯ</h1>

    <div class="site-login">
        <div class="row">
            <div class="col-md-12">
                <?php $form = ActiveForm::begin([
                    'id' => 'reset-password-form',
                    'fieldConfig' => ['template'=>"<span class='input'>{input}\n{label}</span>\n{error}",],
                ]); ?>

                <div class="row">
                    <div class="col-md-12 text-center" style="margin-bottom: 20px;">
                        Введите ваш новый пароль
                    </div>
                </div>

                <?= $form->field($model, 'password', [])
                    ->passwordInput(['class'=>'input__field'])
                    ->label('<span class="input__label-content" data-content="'.$model->getAttributeLabel('password').'">'.$model->getAttributeLabel('password').'</span>', ['class'=>'input__label']) ?>

                <?= $form->field($model, 'password_confirm', [])
                    ->passwordInput(['class'=>'input__field'])
                    ->label('<span class="input__label-content" data-content="'.$model->getAttributeLabel('password_confirm').'">'.$model->getAttributeLabel('password_confirm').'</span>', ['class'=>'input__label']) ?>

                <div class="form-group text-center form-buttons">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'send', 'style' => 'width: 180px;']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>