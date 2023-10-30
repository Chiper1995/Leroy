<?php

/* @var $this yii\web\View */

use yii\bootstrap\Html;

$this->context->layout = "login";

$this->title = 'Спасибо за регистрацию';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="signup-block" style="padding-top: 170px;">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="site-login">
        <div class="row">
            <div class="col-md-12 text-center">
                Спасибо за проявленное терпение, ваша регистрация успешно выполнена.<br />
                После проверки данных нашими специалистами вам будет выслано письмо на электронную почту
                с инструкциями по дальнейшей работе с нашим порталом.

                <div class="bottom-link">
                    <?= Html::a('На главную', ['/']) ?>
                </div>
            </div>
        </div>
    </div>
</div>