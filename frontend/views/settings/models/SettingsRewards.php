<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model common\models\settings\SettingsRewards */

?>

<div class="settings-form">
    <?php $form = ActiveForm::begin(); ?>

    <h3>Основные:</h3>
    <?php foreach ($model->getJournalTypes() as $journalType):?>
        <div class="form-group">
            <?= Html::label($journalType->name, 'settingsrewards-journalType-'.$journalType->id, ['class' => 'control-label']); ?>
            <?= Html::textInput('JournalType['.$journalType->id.']', $journalType->points, ['class' => 'form-control', 'id' => 'settingsrewards-journalType-'.$journalType->id]); ?>
        </div>
    <?php endforeach;?>

    <h3>Дополнительно:</h3>
    <?= $form->field($model, 'visitChangeTime') ?>
    <?= $form->field($model, 'visitClientTime') ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-with-margin-right']) ?>
        <?= Html::submitButton('Отмена', ['class' => 'btn btn-default', 'name' => 'cancel', 'formnovalidate' => '1',]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

