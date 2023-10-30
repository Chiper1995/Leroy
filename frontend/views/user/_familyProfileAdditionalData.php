<?php
/** Форма редактирования дополнительной информации на странице профиля */

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\bootstrap\ActiveForm */
?>

<?= $form->field($model, 'about_user')->textarea(['rows'=>7]) ?>
<?= $form->field($model, 'about_repair')->textarea(['rows'=>7]) ?>