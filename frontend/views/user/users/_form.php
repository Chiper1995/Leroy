<?php

use common\models\User;
use kartik\widgets\Select2;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fio')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'role', [])->widget(Select2::classname(), ['data' => User::getUserRoleList(),]) ?>

    <div class="city-container"<?php if (!in_array($model->role, User::getRoleNeedSetCity())):?> style="display: none;"<?php endif;?>>
        <?= $form->field($model, 'cities', [])->widget(
            Select2::classname(),
            [
                'data' => \common\models\City::getList(),
                'pluginOptions' => ['allowClear' => false,],
                'options' => ['multiple' => true],
            ]) ?>
    </div>
    <?php $this->registerJs('$("#user-role").on("change", function(){if ($(this).val() == "'.implode('" || $(this).val() == "', User::getRoleNeedSetCity()).'") $(".city-container").show(); else $(".city-container").hide();})')?>

    <?php if ($model->isNewRecord):?>
        <?= $this->render("../_formSetPassword", ['form'=>$form, 'model'=>$model])?>
    <?php else:?>
        <?= \yii\bootstrap\Collapse::widget([
            'items' => [
                [
                    'label' => 'Сменить пароль',
                    'content' => $this->render("../_formSetPassword", ['form'=>$form, 'model'=>$model])
                ]
            ]
        ])?>
    <?php endif;?>

    <?= $form->field($model, 'about_user')->textarea(['rows'=>7]) ?>
    <?= $form->field($model, 'about_repair')->textarea(['rows'=>7]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-with-margin-right']) ?>
        <?= Html::a('Отмена', Url::to(Yii::$app->user->getReturnUrl(['users/index'])), ['class' => 'btn btn-default',]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
