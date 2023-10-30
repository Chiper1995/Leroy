<?php
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

    <?php if(!empty($curatorId)) : ?>
        <?= Html::hiddenInput('NewDialogForm[users_id][]', $curatorId) ?>
    <?php else: ?>
        <?= Html::hiddenInput('NewDialogForm[users_id][]', User::getReserveCurator()->id) ?>
    <?php endif; ?>

    <?= $form->field($model, 'subject')?>

    <?= $form->field($model, 'message')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic',
        'clientOptions' => [
            'contentsCss'=>$this->render('@frontend/web/css/ckeditor.min.css'),
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
