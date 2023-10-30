<?php

/* @var $this yii\web\View */

/* @var $form yii\bootstrap\ActiveForm */

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\web\View;
use yii\web\JsExpression;

?>
<?php
$script = <<<JS
   function select_city() {
        $('#usershop-shop').select2('val', '');
        if($('#usershop-city').val()==='') 
            $('#usershop-city').parent().removeClass('input--filled');
        else 
            $('#usershop-city').parent().addClass('input--filled');
    }
    
    function select_shop() {
        if($('#usershop-shop').val()==='') 
            $('#usershop-shop').parent().removeClass('input--filled');
        else 
            $('#usershop-shop').parent().addClass('input--filled');
    }
JS;
$this->registerJs($script, View::POS_READY); ?>
<?php $form = ActiveForm::begin([
    'id' => 'form-registration',
    'action' => ['employee/user-shop'],
    'fieldConfig' => [
        'template' => "<span class='input'>{input}\n{label}</span>\n{error}",
    ],
]); ?>
<div class="row">
    <div class="col-md-12 text-center" style="margin-bottom: 20px;">
        Чтобы зарегистрироваться на сайте, заполни форму ниже
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-xs-6">
        <?php echo $form->field($model, 'name', [])
            ->input('text', ['class' => 'input__field'])
            ->label('<span class="input__label-content" data-content="' . $model->getAttributeLabel('name') . '">' . $model->getAttributeLabel('name') . '</span>', ['class' => 'input__label']) ?>

    </div>
    <div class="col-md-6 col-xs-6">
        <?php echo $form->field($model, 'surname', [])
            ->input('text', ['class' => 'input__field'])
            ->label('<span class="input__label-content" data-content="' . $model->getAttributeLabel('surname') . '">' . $model->getAttributeLabel('surname') . '</span>', ['class' => 'input__label']) ?>

    </div>
</div>

<?php echo $form->field($model, 'email', [])
    ->input('text', ['class' => 'input__field'])
    ->label('<span class="input__label-content" data-content="' . $model->getAttributeLabel('email') . '">' . $model->getAttributeLabel('email') . '</span>', ['class' => 'input__label']) ?>

<div class="row col-md-12">
    Какое высказывание лучше всего описывает твою ситуацию:
    <?php echo $form->field($model, 'activity')->radioList(
        [
            'shop' => 'Меня назначили ответственным от магазина за проект Семьи',
            'shopModerator' => 'Я помогаю ответственному от магазина или мне просто интересно почитать мнение наших клиентов'],
        [
            'item' => function ($index, $label, $name, $checked, $value) {
                $id = 'activity-' . $value;
                return
                    Html::beginTag('div', ['class' => 'radio']) .
                    Html::radio($name, $checked, ['value' => $value, 'id' => $id, 'label' => $label]) .
                    Html::endTag('div');
            },
            'itemOptions' => ['class' => '']
        ])->label(false); ?>
</div>
<?php echo $form->field($model, 'city', [])->widget(Select2::classname(), [
    'data' => \common\models\City::getList(),
    'language' => 'ru',
    'theme' => Select2::THEME_BOOTSTRAP,
    'options' => ['placeholder' => '', 'class' => 'input__field'],
    'pluginOptions' => [
        'allowClear' => false,

    ],
    'pluginEvents' => [
        "select2:select" => 'select_city',
    ]
])
    ->label('<span class="input__label-content" data-content="' . $model->getAttributeLabel('city') . '">' . $model->getAttributeLabel('city') . '</span>', ['class' => 'input__label']) ?>

<?php echo $form->field($model, 'shop', [])->widget(Select2::classname(), [
    'language' => 'ru',
    'theme' => Select2::THEME_BOOTSTRAP,
    'options' => ['placeholder' => '', 'class' => 'input__field'],
    'pluginOptions' => [
        'allowClear' => false,
        'ajax' => [
            'url' => \yii\helpers\Url::to(['employee/shops']),
            'dataType' => 'json',
            'data' => new JsExpression('function(params) {let id = $("#usershop-city").val();  return {term:params.term, id:id}; }'),
        ],
        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
        'templateResult' => new JsExpression('function(data) { return data.text; }'),
        'templateSelection' => new JsExpression('function (data) { return data.text; }'),

    ],
    'pluginEvents' => [
        "select2:select" => 'select_shop',
    ]
])
    ->label('<span class="input__label-content" data-content="' . $model->getAttributeLabel('shop') . '">' . $model->getAttributeLabel('shop') . '</span>', ['class' => 'input__label']) ?>

<?php echo $form->field($model, 'login', [])
    ->input('text', ['class' => 'input__field'])
    ->label('<span class="input__label-content" data-content="' . $model->getAttributeLabel('login') . '">' . $model->getAttributeLabel('login') . '</span>', ['class' => 'input__label']) ?>


<div class="row">
    <div class="col-md-6 col-xs-6">
        <?php echo $form->field($model, 'password', [])
            ->passwordInput(['class' => 'input__field'])
            ->label('<span class="input__label-content" data-content="' . $model->getAttributeLabel('password') . '">' . $model->getAttributeLabel('password') . '</span>', ['class' => 'input__label']) ?>

    </div>
    <div class="col-md-6 col-xs-6">
        <?php echo $form->field($model, 'password_confirm', [])
            ->passwordInput(['class' => 'input__field'])
            ->label('<span class="input__label-content" data-content="' . $model->getAttributeLabel('password_confirm') . '">' . $model->getAttributeLabel('password_confirm') . '</span>', ['class' => 'input__label']) ?>

    </div>
</div>

<div class="form-group text-center form-buttons">
    <?php echo Html::submitButton('Далее &rarr;', ['class' => 'btn btn-primary', 'name' => 'next', 'style' => 'width: 180px;']) ?>
</div>

<?php ActiveForm::end(); ?>
