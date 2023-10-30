<?php
use common\models\User;
use yii\web\View;
use yii\bootstrap\Html;
/* @var \yii\web\View $this */
/* @var User $user */
?>
<?php $this->registerCssFile('@web/css/enjoyhint.min.css')?>
<?php $this->registerJsFile('@web/js/enjoyhint.min.js', ['position' => View::POS_END, 'depends'=>\yii\web\JqueryAsset::className()])?>
<?php $this->registerJsFile('@web/js/guide.min.js', ['position' => View::POS_END, 'depends'=>\yii\web\JqueryAsset::className()])?>

<?php echo $this->render('_modal');?>
