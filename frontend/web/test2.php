<?php

set_time_limit(3000);
ini_set('max_execution_time', 3000);

//define('YII_ENV', IS_DEV_SERVER ? 'dev' : 'prod');


defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

ini_set('display_errors', 'On');


defined('YII_APP_BASE_PATH') or define('YII_APP_BASE_PATH', dirname(dirname(__DIR__)));

require(YII_APP_BASE_PATH . '/vendor/autoload.php');
require(YII_APP_BASE_PATH . '/vendor/yiisoft/yii2/Yii.php');
require(YII_APP_BASE_PATH . '/common/config/bootstrap.php');

//$config = require(YII_APP_BASE_PATH . '/tests/config/acceptance.php');

//$config =require(YII_APP_BASE_PATH . '/common/config/environments/params-dev.php');

$config = require(YII_APP_BASE_PATH . '/frontend/config/main.php');
//echo '<pre>'.\yii\helpers\VarDumper::dumpAsString($config, 10, true).'</pre>'; die();

$application = new yii\web\Application($config);

$application->run(1);


$model = null;


use common\models\User;
use dosamigos\ckeditor\CKEditor;
use frontend\models\dialog\NewDialogForm;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var \frontend\models\dialog\NewDialogForm $model */
/* @var $createdDialogId integer */
?>

<?php
if ($model==null)
    $model = new NewDialogForm();

$users = \common\models\Dialog::getUsersForDialog();
// Убираем себя
$_id = Yii::$app->user->id;
foreach($users as $key => $user) {
    if ($user->id == $_id) {
        unset($users[$key]);
        break;
    }
}
$curatorId = Yii::$app->user->identity->curator_id;
?>

<div class="add-dialog-form">
    <?php $form = ActiveForm::begin(['id'=>'add-dialog-form', 'enableAjaxValidation' => false, 'enableClientValidation' => false, 'options'=>['data-pjax' => '0']]); ?>

    <?= $form->field($model, 'users_id')->widget(\kartik\widgets\Select2::className(), [
        'model' => $model,
        'attribute' => 'user_id',
        'data' => \yii\helpers\ArrayHelper::map($users, 'id', 'fio'),
        'pluginOptions' => [
            'allowClear' => false,
            'templateResult' => new \yii\web\JsExpression('formatUsers')
		],
        'options' => ['multiple' => true, 'placeholder'=>'Выбери получателей'],
    ]) ?>

    <?= $form->field($model, 'subject')?>

    <?= $form->field($model, 'message')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic',
        'clientOptions' => [

            'height'=>200,
            'enterMode'=>new \yii\web\JsExpression('CKEDITOR.ENTER_BR'),
            'shiftEnterMode'=>new \yii\web\JsExpression('CKEDITOR.ENTER_BR'),
            'autoParagraph'=>false,
        ],
    ])?>

    <div style="margin-top: 15px;">
        <?= Html::submitButton('Отправить сообщение', ['name' => 'send_message', 'id'=>'send-message', 'class' => 'btn btn-primary btn-with-margin-right']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$usersArray = [];
/**@var User $user*/
foreach($users as $user) {
    $usersArray[$user->id] = [
        'photo'=>(strlen($user->photo) > 0) ? $user->getPhotoThumb(32, 32) : null,
        'family_name'=>Html::encode($user->family_name),
        'is_curator'=>$user->id === $curatorId,
    ];
}
$usersArray = \yii\helpers\Json::encode($usersArray);
?>

<?php $this->registerJs(
    <<<JS
    var _users = {$usersArray};
    function formatUsers(state) {
        if (!state.id) { return state.text; }

        var photo = '';
        if (_users[state.id]['photo'] == null) {
            photo = '<div class="img-circle"><span class="glyphicon glyphicon-user"></span></div>';
        }
        else {
            photo = '<img src="'+ _users[state.id]['photo'] +'" class="img-circle"/>';
        }

        var __el = $(
            '<div class="media dialog-users-select">'+
                '<div class="media-left">'+photo+'</div>'+
                '<div class="media-body"></div>'+
            '</div>'
        );
        
        var __t = $('<br><span></span>').appendTo(__el.find('.media-body').text(state.text));
        if (_users[state.id]['family_name'] != null) {
        	__t.html(_users[state.id]['family_name']);
        }
        if (_users[state.id]['is_curator']) {
        	__t.html('Ваш куратор');
        }
        
        return __el;
    }
JS
    , \yii\web\View::POS_HEAD);?>

<?php if ($createdDialogId != null):?>
    <?php
    $url = \yii\helpers\Url::toRoute(['dialog/index', 'dialog'=>$createdDialogId]);
    $this->registerJs(
        <<<JS
        jQuery.pjax.reload('#dialogs-pjax', {url: '{$url}'});
JS
	, \yii\web\View::POS_READY);?>
<?php endif;?>
<?php $this->registerJs(
    <<<JS
    $('#send-message').on('click',function(){
        if($('.cke_editable.cke_editable_themed.cke_contents_ltr.cke_show_borders').val() !== ""){
            $('#send-message').hide();
            }
    });
JS
    , \yii\web\View::POS_LOAD);?>
