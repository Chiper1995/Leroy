<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use common\widgets\Alert;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

$this->context->layout = "login";

$this->title = 'Добро пожаловать!';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="auth-block">
    <h1>ДОБРО ПОЖАЛОВАТЬ!</h1>

    <div class="site-login">
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
        ]); ?>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="border-right: solid 1px #d1d1d1; padding-right: 40px;">
                <?= $form->field($model, 'username', ['template'=>"<span class='input'>{input}\n{label}</span>\n{error}",])
                        ->input('text', ['class'=>'input__field'])
                        ->label('<span class="input__label-content" data-content="'.$model->getAttributeLabel('username').'">'.$model->getAttributeLabel('username').'</span>', ['class'=>'input__label']) ?>

                <?= $form->field($model, 'password', ['template'=>"<span class='input'>{input}\n{label}</span>\n{error}",])
                        ->passwordInput(['class'=>'input__field'])
                        ->label('<span class="input__label-content" data-content="'.$model->getAttributeLabel('password').'">'.$model->getAttributeLabel('password').'</span>', ['class'=>'input__label'])
                ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="form-group text-center">
                    <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

                <div class="bottom-link" style="margin-bottom: 0.2em;">
                    <?= Html::a('Забыли пароль?', ['site/request-password-reset']) ?>
                </div>

                <div class="bottom-link" style="margin-top: 0;">
                    <?= Html::a('Регистрация для сотрудников Леруа Мерлен', ['employee/index']) ?>
                </div>
            </div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-top: 18px; padding-left: 40px;">
				<h2>Впервые на нашем сайте?</h2>
				<h3><i class="fa fa-home"></i><span>Находитесь в процессе ремонта или строительства?</span></h3>
				<h3><i class="fa fa-users"></i><span>Присоединяйтесь к нашему сообществу любителей ремонта!</span></h3>

				<div class="form-group text-center" style="margin-top: 40px">
					<?= Html::a('Заполните анкету', ['invite/index'], ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
					<div style="margin-top: 6px">и мы свяжемся с Вами</div>
				</div>
				<div class="help-block text-center">
					<small>* В случае соответствия условиям участия в проекте</small>
				</div>
			</div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<div class="signup-block" style="padding: 15px 0; margin-top: -65px;">
    <?= Alert::widget() ?>
</div>


<?php $this->registerJs("
    $('#login-form').on('ajaxBeforeSend', function (event, jqXHR, settings) {
        $('.site-login').addClass('loading');
    }).on('ajaxComplete', function (event, jqXHR, textStatus) {
        $('.site-login').removeClass('loading');
    });
    ",
    View::POS_END
);?>
