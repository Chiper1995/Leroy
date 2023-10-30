<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use common\widgets\Alert;
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
        <?= Alert::widget() ?>
        <div class="row">
            <div class="col-md-12">
                <?php $form = ActiveForm::begin([
                    'id' => 'request-password-reset-form',
                    'fieldConfig' => ['template'=>"<span class='input'>{input}\n{label}</span>\n{error}",],
                ]); ?>

                <div class="row">
                    <div class="col-md-12 text-center" style="margin-bottom: 20px;">
                        Введите ваш email адрес, который использовался при регистрации. На него будет отправлена ссылка для сброса пароля.
                    </div>
                </div>

                <?= $form->field($model, 'email', [])
                    ->input('text', ['class'=>'input__field'])
                    ->label('<span class="input__label-content" data-content="'.$model->getAttributeLabel('email').'">'.$model->getAttributeLabel('email').'</span>', ['class'=>'input__label']) ?>

                <div class="form-group text-center form-buttons">
                    <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'send', 'style' => 'width: 180px;']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>