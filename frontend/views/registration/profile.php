<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\registration\Profile */

use kartik\widgets\Select2;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

$this->context->layout = "login";

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;

$apikey = \Yii::$app->params['apikey'];
?>

<?php $this->registerJs("
    $('#form-registration').on('ajaxBeforeSend', function (event, jqXHR, settings) {
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
                    'id' => 'form-registration',
                    'fieldConfig' => ['template'=>"<span class='input'>{input}\n{label}</span>\n{error}",],
                ]); ?>

                <div class="row">
                    <div class="col-md-12 text-center" style="margin-bottom: 20px;">
                        Чтобы зарегистрироваться на сайте, заполните форму ниже
                    </div>
                </div>

                <?= $form->field($model, 'family_name', [])
                    ->input('text', ['class'=>'input__field'])
                    ->label('<span class="input__label-content" data-content="'.$model->getAttributeLabel('family_name').'">'.$model->getAttributeLabel('family_name').'</span>', ['class'=>'input__label']) ?>

                <?= $form->field($model, 'fio', [])
                    ->input('text', ['class'=>'input__field'])
                    ->label('<span class="input__label-content" data-content="'.$model->getAttributeLabel('fio').'">'.$model->getAttributeLabel('fio').'</span>', ['class'=>'input__label']) ?>

                <div class="row">
                    <div class="col-md-6 col-xs-6">
                        <?= $form->field($model, 'email', [])
                            ->input('text', ['class'=>'input__field'])
                            ->label('<span class="input__label-content" data-content="'.$model->getAttributeLabel('email').'">'.$model->getAttributeLabel('email').'</span>', ['class'=>'input__label']) ?>
                    </div>
                    <div class="col-md-6 col-xs-6">
                        <?= $form->field($model, 'phone', [])
                            ->input('text', ['class'=>'input__field'])
                            ->label('<span class="input__label-content" data-content="'.$model->getAttributeLabel('phone').'">'.$model->getAttributeLabel('phone').'</span>', ['class'=>'input__label']) ?>
                    </div>
                </div>

                <?= $form->field($model, 'city_id', [])->widget(Select2::classname(), [
                        'data' => \common\models\City::getList(),
                        'language' => 'ru',
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder'=>'', 'class'=>'input__field'],
                        'pluginOptions' => [
                            'allowClear' => false,

                        ],])
                    ->label('<span class="input__label-content" data-content="'.$model->getAttributeLabel('city_id').'">'.$model->getAttributeLabel('city_id').'</span>', ['class'=>'input__label']) ?>

				<div id="form-adress-group" style="position:relative; z-index:1000; margin-bottom:15px;">
					<?= $form->field($model, 'address', ['options' => ['class' => 'field-adress-suggest'], 'validateOnType' => true, 'enableAjaxValidation' => false])
						->input('text', ['class'=>'input__field', 'id' => 'adress-suggest'])
						->label('<span class="input__label-content" data-content="'.$model->getAttributeLabel('address').'">'.$model->getAttributeLabel('address').'</span>', ['class'=>'input__label']) ?>

					<p class="help-block help-block-error" id="form-error-message" style="display: none;"></p>
				</div>

				<?= Html::input('hidden', 'latitude', '', ['id'=>'form-latitude'])?>
                <?= Html::input('hidden', 'longitude', '', ['id'=>'form-longitude'])?>

                <?= $form->field($model, 'username', [])
                    ->input('text', ['class'=>'input__field'])
                    ->label('<span class="input__label-content" data-content="'.$model->getAttributeLabel('username').'">'.$model->getAttributeLabel('username').'</span>', ['class'=>'input__label']) ?>

                <div class="row">
                    <div class="col-md-6 col-xs-6">
                        <?= $form->field($model, 'password', [])
                            ->passwordInput(['class'=>'input__field'])
                            ->label('<span class="input__label-content" data-content="'.$model->getAttributeLabel('password').'">'.$model->getAttributeLabel('password').'</span>', ['class'=>'input__label']) ?>
                    </div>
                    <div class="col-md-6 col-xs-6">
                        <?= $form->field($model, 'password_confirm', [])
                            ->passwordInput(['class'=>'input__field'])
                            ->label('<span class="input__label-content" data-content="'.$model->getAttributeLabel('password_confirm').'">'.$model->getAttributeLabel('password_confirm').'</span>', ['class'=>'input__label']) ?>
                    </div>
                </div>

				<div class="row">
					<div class="col-md-12">
						<?= $form->field($model, 'agreed', [])->checkbox(['label' => $model->getAttributeLabel('agreed') . ' <a href="#" data-toggle="modal" data-target="#agreedInfo">Подробнее</a>']); ?>
					</div>
				</div>

                <div class="form-group text-center form-buttons">
                    <?= Html::submitButton('Далее &rarr;', ['class' => 'btn btn-primary', 'name' => 'next', 'style' => 'width: 180px;']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<div id="agreedInfo" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Согласие на обработку персональных данных</h4>
			</div>
			<div class="modal-body">
				<p class="text-justify">Настоящим я даю свое согласие на обработку моих персональных данных (фамилия, имя, отчество, адрес места проживания, контактный телефон, адрес электронной почты, мое изображение), в том числе их сбор, систематизацию, накопление, хранение, уточнение (обновление, изменение), использование, уничтожение, в том числе с использованием технических средств, уполномоченными на это сотрудниками ООО «Леруа Мерлен Восток» в соответствии с законодательством Российской Федерации о персональных данных Российской Федерации. Я проинформирован, что обработка моих персональных данных осуществляется в целях, перечисленных выше.</p>
				<p class="text-justify">Согласие на обработку персональных данных действует в течение 10 лет. Персональные данные хранятся в соответствии с требованиями законодательства Российской Федерации на условиях конфиденциальности в течение 10 лет с даты их получения, после чего персональные данные подлежат уничтожению.</p>
			</div>
			<div class="modal-footer">
				<?= \yii\bootstrap\Html::button('Ок', ['class'=>'btn btn-default cancel-btn', 'data-dismiss'=>'modal',])?>
			</div>
		</div>
	</div>
</div>

<!-- Ассинхронная загрузка API карты в head -->
<?php $this->registerJsFile('https://api-maps.yandex.ru/2.1?apikey='. $apikey. '&load=package.full&lang=ru_RU', ['position' => View::POS_HEAD])?>
<?php $this->registerJsFile('@web/js/process-input-adress.min.js', ['position' => View::POS_END, 'depends'=>[\yii\web\JqueryAsset::className()]])?>

<?php $this->registerJsFile('@web/js/mailcheck.min.js', ['position' => View::POS_END, 'depends'=>\yii\web\JqueryAsset::className()])?>
<?php $this->registerJsFile('@web/js/mailcheck-callback.min.js', ['position' => View::POS_END, 'depends'=>\yii\web\JqueryAsset::className()])?>
