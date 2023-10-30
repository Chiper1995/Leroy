<?php
/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\bootstrap\ActiveForm */
?>

<?php echo $form->field($model, 'set_password')->passwordInput() ?>
<?php echo $form->field($model, 'set_password_confirm')->passwordInput() ?>