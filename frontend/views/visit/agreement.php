<?php

/* @var $model common\models\Visit */
/* @var $this yii\web\View */

use common\models\Visit;
use frontend\helpers\TimeListToPrettyHtmlTimeList;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\web\JsExpression;


$this->title = 'Визит '.Yii::$app->formatter->asDate($model->date).', время: '. Visit::getAllTimeNamesList()[$model->time];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_view', [
    'model' => $model,
]) ?>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-12 content-container" style="padding-bottom: 0; margin-top: 0px;">
        <?= Html::activeHiddenInput($model, 'time', ['id'=>'time'])?>
        <div class="form-group form-buttons">
            <?= Html::submitButton(Html::icon('ok').'&nbsp;Согласовать', ['name' => 'agreed', 'class' => 'btn btn-primary btn-with-margin-right',]) ?>
            <?= Html::submitButton(Html::icon('time').'&nbsp;Изменить время', ['name' => 'changed_time', 'class' => 'btn btn-primary btn-with-margin-right', 'onclick'=>'return false;']) ?>
            <?= Html::submitButton(Html::icon('remove').'&nbsp;Отказаться от визита', ['name' => 'canceled', 'class' => 'btn btn-danger btn-with-margin-right',]) ?>
            <?= Html::a('Отмена', Url::to(Yii::$app->user->getReturnUrl(['journal/all-journals'])), ['class' => 'btn btn-default',]) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
$select = \kartik\widgets\Select2::widget([
    'name' => 'dialog-time',
    'value' => $model->time,
    'id' => 'dialog-time',
    'data' => TimeListToPrettyHtmlTimeList::convert(\common\models\Visit::getAllTimeNamesList()),
    'pluginOptions' => [
        'templateResult' => new JsExpression('function format(state) {return jQuery.parseHTML(state.text);}'),
        'templateSelection' => new JsExpression('function format(state) {return jQuery.parseHTML(state.text);}'),
    ],
]);
$select = trim(preg_replace('/\s+/', ' ', $select));
?>

<?php $this->registerJs(
<<<JS
    $('button[name=changed_time]').on('click', function(){
        _this = $(this);

        if (_this.attr('data-dialog-ok') == '1') {
            _this.removeAttr('data-dialog-ok');
            return true;
        }

        bootbox.dialog({
                title: 'Выберите время',
                message:
                    '<div class="bootbox-body"><form class="bootbox-form">' +
                    '$select' +
                    '</form></div>',
                buttons: {
                    success: {
                        label: "Сохранить",
                        className: "btn-primary btn-with-margin-right",
                        callback: function() {
                            $('#time').val($('#dialog-time').val());
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

        $('#dialog-time').on('select2:opening', initS2Open).on('select2:unselecting', initS2Unselect);
        var s = {"templateResult":function format(state) {return jQuery.parseHTML(state.text);},"templateSelection":function format(state) {return jQuery.parseHTML(state.text);},"theme":"bootstrap","width":"100%","placeholder":"","language":"ru"};
        jQuery.when(jQuery('#dialog-time').select2(s)).done(initS2Loading('dialog-time', '.select2-container--bootstrap', '', true));

        return false;
    });
    $('button[name=changed_time]').removeAttr('onclick');
JS
);?>