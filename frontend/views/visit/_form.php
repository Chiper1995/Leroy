<?php

use dosamigos\ckeditor\CKEditor;
use frontend\helpers\TimeListToPrettyHtmlTimeList;
use kartik\datecontrol\DateControl;
use kartik\widgets\DatePicker;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\Visit */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="row">
    <div class="col-md-12 content-container" style="padding-bottom: 0; margin-bottom: 15px;">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
</div>

<?php $form = ActiveForm::begin(['enableClientValidation'=>false, 'enableAjaxValidation'=>false, 'id'=>'task-form']); ?>
<div class="row">
    <div class="col-md-12 col-sm-12 content-container" style="padding-bottom: 0; margin-bottom: 15px; min-height: 356px">
        <?= $form->field($model, 'date', ['template' => "{label}\n<div style='max-width:150px;'>{input}</div>\n{hint}\n{error}",])
            ->widget(DateControl::className(), [
                'type'=>DateControl::FORMAT_DATE,
                'options' => [
                    'pluginOptions' => [
                        'startDate' => date('d.m.Y', time()-60*60*24),
                    ],
                ],
            ]
        ) ?>

        <?= $form->field($model, 'time', ['template' => "{label}\n<div style='max-width:150px;'>{input}</div>\n{hint}\n{error}",])
            ->widget(\kartik\widgets\Select2::className(), [
                'model' => $model,
                'attribute' => 'time',
                'data' => TimeListToPrettyHtmlTimeList::convert(\common\models\Visit::getAllTimeNamesList()),
                'pluginOptions' => [
                    'templateResult' => new JsExpression('function format(state) {return jQuery.parseHTML(state.text);}'),
                    'templateSelection' => new JsExpression('function format(state) {return jQuery.parseHTML(state.text);}'),
                ],
            ]
        ) ?>

        <?= $form->field($model, 'user_id')->widget(\kartik\widgets\Select2::className(), [
            'model' => $model,
            'attribute' => 'user_id',
            'data' => \common\models\Visit::getFamiliesList(),
            'pluginOptions' => [],
        ]) ?>

        <?= $form->field($model, 'description')->widget(CKEditor::className(), [
            'options' => ['rows' => 6,],
            'clientOptions'=>['height'=>150],
            'preset' => 'full'
        ]) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12 content-container" style="padding-bottom: 0;">
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-with-margin-right', 'name' => 'save',]) ?>
            <?= Html::submitButton('Отмена', ['class' => 'btn btn-default', 'name' => 'cancel', 'formnovalidate' => '1',]) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

