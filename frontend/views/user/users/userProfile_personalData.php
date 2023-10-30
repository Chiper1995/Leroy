<?php
/** Форма редактирования личной информации на странице профиля */

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\bootstrap\ActiveForm */
?>
<?= $form->field($model, 'username')->textInput(['maxlength' => true, 'readonly'=>'readonly']) ?>
<?= $form->field($model, 'fio')->textInput(['maxlength' => true])->label('Ваше ФИО') ?>
<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

<?= \yii\bootstrap\Collapse::widget([
    'items' => [
        [
            'label' => 'Сменить пароль',
            'content' => $this->render("../_formSetPassword", ['form'=>$form, 'model'=>$model])
        ]
    ]
])?>
