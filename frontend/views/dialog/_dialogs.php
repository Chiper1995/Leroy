<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\web\View;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var \frontend\models\dialog\MyDialogsSearch $searchModel */

$users = \common\models\Dialog::getUsersForDialog();
// Убираем себя
$_id = Yii::$app->user->id;
foreach($users as $key => $user) {
    if ($user->id == $_id) {
        unset($users[$key]);
        break;
    }
}
?>

<?php $this->registerJsFile('@web/js/dialogs.min.js', ['position' => View::POS_END, 'depends'=>[\yii\web\JqueryAsset::className()]])?>
<?php $this->registerJs('new Dialogs();')?>
<div style="margin-bottom: 15px;">
    <?php $form = ActiveForm::begin(['id'=>'dialogFilterForm', 'method'=>'GET', 'action'=>['dialog/index'], 'enableAjaxValidation' => false, 'enableClientValidation' => false, 'options'=>['data-pjax' => '0']]); ?>
        <?= $form->field($searchModel, 'users_id')->widget(\kartik\widgets\Select2::className(), [
            'model' => $searchModel,
            'attribute' => 'user_id',
            'data' => \yii\helpers\ArrayHelper::map($users, 'id', 'fio'),
            'pluginOptions' => [
                'allowClear' => false,
                'templateResult' => new \yii\web\JsExpression('formatUsers')],
            'options' => ['multiple' => true, 'placeholder'=>'Выбери получателей'],
        ]) ?>
    <?php ActiveForm::end(); ?>
</div>
<div class="dialog-list">
    <?php if (($dataProvider != null)and($dataProvider->count > 0)):?>
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '__dialogItem',
            'layout' => '{items}<div class="row"><div class="col-md-12"><div class="text-center">{pager}</div></div></div>',
        ]); ?>
    <?php else:?>
        <div class="no-visits">
            <p style="font-size: 18px; padding-top: 10px;">У тебя пока нет диалогов</p>
        </div>
    <?php endif;?>
</div>
<?php $this->registerJs(
    <<<JS
    $('div[data-key]').each(function(){
     var timestamp = $(this).find('#timestamp').html().trim();
      var myDate = new Date(timestamp *1000);
     $(this).find('.dialog-last-message-time').html(myDate.toLocaleString());
    });
JS
    , \yii\web\View::POS_LOAD);?>

