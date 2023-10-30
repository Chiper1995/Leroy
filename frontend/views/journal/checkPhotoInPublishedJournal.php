<?php

/* @var $model common\models\Journal */
/* @var $this yii\web\View */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
use kartik\widgets\Select2;
use common\models\Journal;

$this->title = $model->subject;
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_view', [
    'model' => $model,
    'displayOnCheckPhotos' => true,
]) ?>

<?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-12 content-container" style="padding-bottom: 0; margin-top: 0px;">
            <?= Html::activeHiddenInput($model, 'return_photo_reason', ['id'=>'return_photo_reason'])?>
            <div class="form-group">
                <?= Html::submitButton(Html::icon('ok').'&nbsp;Фотографии проверены', ['name' => 'checked', 'class' => 'btn btn-primary btn-with-margin-right',]) ?>
                <?= Html::submitButton('Фотографии не приняты', ['name' => 'returnToEdit', 'class' => 'btn btn-danger btn-with-margin-right', 'onclick'=>'return false;']) ?>
                <?= Html::a('Отмена', Url::to(Yii::$app->user->getReturnUrl(['journal/all-journals'])), ['class' => 'btn btn-default',]) ?>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>

<?php $this->registerJs(
    <<<JS
    $('button[name=returnToEdit]').on('click', function(){
        _this = $(this);

        if (_this.attr('data-dialog-ok') == '1') {
            _this.removeAttr('data-dialog-ok');
            return true;
        }

        bootbox.dialog({
                title: 'Введи причину отказа в публикации фотографий',
                message:
                    '<div class="bootbox-body"><form class="bootbox-form"><textarea id="dialog-return-reason" class="bootbox-input bootbox-input-text form-control" rows="10"></textarea></form></div>',
                buttons: {
                    success: {
                        label: "Сохранить",
                        className: "btn-primary btn-with-margin-right",
                        callback: function() {
                            $('#return_photo_reason').val($('#dialog-return-reason').val());
                            _this.attr('data-dialog-ok', '1');
                            _this.click();
                        }
                    },
                    cancel: {
                        label: "Отмена",
                        className: "btn-default",
                        callback: function() {}
                    },
                }
            }
        );
        return false;
    });
    $('button[name=returnToEdit]').removeAttr('onclick');
JS
);?>