<?php
/** Форма редактирования личной информации на странице профиля */

use yii\helpers\Html;
use yii\web\View;
use common\models\UserLocation;

$apikey = Yii::$app->params['apikey'];
$countAdresses = count($formProfile->adresses);
?>

<?= $form->field($model, 'username')->textInput(['maxlength' => true, 'readonly'=>'readonly']) ?>
<?= $form->field($model, 'fio')->textInput(['maxlength' => true])->label('Ваше ФИО') ?>
<?= $form->field($model, 'family_name')->textInput(['maxlength' => true])->hint('Например, Семья Ивановых') ?>
<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
<div class="row">
    <div class="col-md-8">
        <?= $form->field($formProfile, 'adress')->textInput(['class' => 'form-control adress-suggest']) ?>
    </div>
    <div class="col-md-4">
        <div class="pull-right">
            <?= Html::button(
                '<i class="glyphicon glyphicon-plus"></i> Добавить адрес другого ремонта',
                ['class' => 'btn btn-primary btn-sm multi-input-button', 'style' => 'margin-top: 23px;']) ?>
        </div>
    </div>
</div>

<div class="multi-input">
    <?php foreach ($formProfile->adresses as $key => $adress): ?>
        <?= $form->field($adress, "[$key]adress")->textInput(['class' => 'form-control adress-suggest'])->label('Адрес другого ремонта') ?>
    <?php endforeach; ?>
    <?php $formProfile->adresses[] = new UserLocation() ?>
    <div class="additional-adress hidden">
        <?= $form->field(end($formProfile->adresses), "[$countAdresses]adress")->textInput(['class' => 'form-control adress-suggest'])->label('Адрес другого ремонта') ?>
    </div>
</div>

<?php if ($model->isNewRecord):?>
    <?= $this->render("_formSetPassword", ['form'=>$form, 'model'=>$model])?>
<?php else:?>
    <?= \yii\bootstrap\Collapse::widget([
        'items' => [
            [
                'label' => 'Сменить пароль',
                'content' => $this->render("_formSetPassword", ['form'=>$form, 'model'=>$model])
            ]
        ]
    ])?>
<?php endif;?>

<?php $this->registerJs(
    '$(".multi-input-button").on("click", function() {
        $(".additional-adress").toggleClass("hidden");
    });'
);?>

<!-- Ассинхронная загрузка API карты в head -->
<?php $this->registerJsFile('https://api-maps.yandex.ru/2.1?apikey='. $apikey. '&load=package.full&lang=ru_RU', ['position' => View::POS_HEAD])?>
<?php $this->registerJsFile('@web/js/input-adress-family.min.js', ['position' => View::POS_END, 'depends'=>[\yii\web\JqueryAsset::className()]])?>
