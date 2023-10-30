<?php

use common\models\User;
use dosamigos\ckeditor\CKEditor;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $viewedDialog \common\models\Dialog */
/* @var $addMessageForm \frontend\models\dialog\AddMessageForm */
?>

<?php
/**@var User $_prevAuthor*/
$messages = [];
$_messages = $viewedDialog->messages;
$index = 0;
$_prevAuthor = null;
$_message = null;
while ($index < count($_messages)) {
    if (($_prevAuthor == null)or($_prevAuthor->id!=$_messages[$index]->user->id)) {
        if ($_message != null) {
            $messages[] = $_message;
        }
        $_message = ['time' => $_messages[$index]->created_at, 'user' => $_messages[$index]->user, 'message' => [$_messages[$index]->message]];
    }
    else {
        $_message['time'] = $_messages[$index]->created_at;
        $_message['message'][] = $_messages[$index]->message;
    }
    $_prevAuthor = $_messages[$index]->user;
    $index++;
}
$messages[] = $_message;
?>

<div class="dialog-header">
    <?= Html::encode($viewedDialog->subject); ?>
</div>
<?php \yii\widgets\Pjax::begin(['id'=>'messages-list-pjax']); ?>
<div class="messages-list" id="messages-list">
    <?= ListView::widget([
        'dataProvider' => new \yii\data\ArrayDataProvider(['allModels'=>$messages]),
        'itemView' => '__messageItem',
        'layout' => '{items}<div class="row"><div class="col-md-12"><div class="text-center">{pager}</div></div></div>',
    ]); ?>
</div>
<?php $this->registerJs(
    <<<JS
    var ml = document.getElementById("messages-list");
    ml.scrollTop = ml.scrollHeight;
JS
, \yii\web\View::POS_LOAD);?>
<?php $this->registerJs(
    <<<JS
    if (window.messagesListInterval!=undefined)
        clearInterval(window.messagesListInterval);
    if ($('#messages-list-pjax').length > 0)
        window.messagesListInterval = setInterval(
            function(){
                if ($('#messages-list-pjax').length > 0)
                    jQuery.pjax.reload('#messages-list-pjax')
                else
                   clearInterval(window.messagesListInterval);
            }, 30000);
JS
    , \yii\web\View::POS_READY);?>
<?php \yii\widgets\Pjax::end(); ?>
<div class="add-message-form">
    <?php if ($addMessageForm != null): ?>
        <?php $form = ActiveForm::begin(['id'=>'add-message-form', 'enableAjaxValidation' => false, 'enableClientValidation' => false, 'options'=>['data-pjax' => '0']]); ?>
        <div class="row">
            <div class="col-md-10">
                <?= $form->field($addMessageForm, 'message')->widget(CKEditor::className(), [
                    'options' => ['rows' => 6],
                    'preset' => 'basic',
                    'clientOptions' => [
                        'contentsCss'=>$this->render('@frontend/web/css/ckeditor.min.css'),
                        'height'=>120,
                        'enterMode'=>new \yii\web\JsExpression('CKEDITOR.ENTER_BR'),
                        'shiftEnterMode'=>new \yii\web\JsExpression('CKEDITOR.ENTER_BR'),
                        'autoParagraph'=>false,
                    ],
                ])?>
            </div>
            <div class="col-md-2">
                <div class="dialog-users-list">
                    <ul>
                    <?php foreach($viewedDialog->users as $user):?>
                        <?php if ($user->id != Yii::$app->user->id):?>
                        <li>
                            <?=\frontend\widgets\UserPhoto\UserPhoto::widget(['user'=>$user, 'size'=>32, 'showTitle'=>true])?>
                        </li>
                        <?php endif;?>
                    <?php endforeach;?>
                    </ul>
                </div>
            </div>
        </div>


        <div style="margin-top: 15px;">
            <?= Html::submitButton('Отправить сообщение', ['name' => 'send_message', 'id'=>'send-message', 'class' => 'btn btn-primary btn-with-margin-right']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    <?php else: ?>
        <div class="row">
            <div class="col-md-12">
                <div class="dialog-users-list">
                    <ul>
                        <?php foreach($viewedDialog->users as $user):?>
                            <?php if ($user->id != Yii::$app->user->id):?>
                            <li>
                                <?=\frontend\widgets\UserPhoto\UserPhoto::widget(['user'=>$user, 'size'=>32, 'showTitle'=>true])?>
                            </li>
                            <?php endif;?>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $this->registerJs(
    <<<JS
    var setFocus = false;
    $(document).on("pjax:end", function (event) {
        if (setFocus)
            CKEDITOR.instances['addmessageform-message'].focus()
    });
    $(document).on("pjax:beforeReplace", function (event) {
    	setFocus = false;
    	var editor = CKEDITOR.instances['addmessageform-message'];
    	if (editor) {
    		setFocus = editor.focusManager.hasFocus;
    	}
    });
JS
    , \yii\web\View::POS_READY);?>

<?php $this->registerJs(
    <<<JS
    $('#send-message').on('click',function(){
        if($('.cke_editable.cke_editable_themed.cke_contents_ltr.cke_show_borders').val() !== ""){
            $('#send-message').hide();
            }
    });

    $('div[data-key]').each(function(){
     var timestamp = $(this).find('#timestamp').html().trim();
      var myDate = new Date(timestamp *1000);
     $(this).find('.message-time').html(myDate.toLocaleString());
    });
JS
    , \yii\web\View::POS_LOAD);?>
