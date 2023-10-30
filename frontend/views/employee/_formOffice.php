<?php

/* @var $this yii\web\View */

/* @var $form yii\bootstrap\ActiveForm */

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\web\View;

?>
<?php
$script = <<<JS
    function change_activity() {
         if($('#useroffice-activity input:checked').val()=='other'){
            $('#block-activity-other').css('display', '');
        }
        else{
            $('#block-activity-other').css('display', 'none');
        } 
    }
JS;
$this->registerJs($script, View::POS_READY); ?>
<?php $form = ActiveForm::begin([
    'id' => 'form-registration',
    'action' => ['employee/user-office'],
    'fieldConfig' => [
        'template' => "<span class='input'>{input}\n{label}</span>\n{error}",
    ],
    'options' => [
        'data-pjax' => true,
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
    Укажи в какой дирекции ты работаешь:
    <?php echo $form->field($model, 'activity')->radioList(
        [
            'marketing' => 'Маркетинг',
            'purchase' => 'Закупки',
            'other' => 'Другое'
        ],
        [
            'item' => function ($index, $label, $name, $checked, $value) {
                $id = 'activity-' . $value;
                return
                    Html::beginTag('div', ['class' => 'radio']) .
                    Html::radio($name, $checked, ['value' => $value, 'id' => $id, 'label' => $label]) .
                    Html::endTag('div');
            },
            'onchange' => 'change_activity()'
        ])->label(false); ?>
</div>

<div class="row col-md-12" id="block-activity-other" style="display: none;">
    Что планируешь делать на платформе?
    <?php echo $form->field($model, 'activity_other')->radioList(
        [
            'viewJournalOnlyAllCities' => 'Читать дневники клиентов и комментировать записи',
            'marketing' => 'Создавать и проверять задания, читать и комментировать записи клиентов',
            'marketing_plus' => 'Сотрудник центрального офиса',
        ],
        [
            'item' => function ($index, $label, $name, $checked, $value) {
                $id = 'activity-other-' . $value;
                return
                    Html::beginTag('div', ['class' => 'radio']) .
                    Html::radio($name, $checked, ['value' => $value, 'id' => $id, 'label' => $label]) .
                    Html::endTag('div');
            }
        ])->label(false); ?>
</div>

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
