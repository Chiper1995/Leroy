<?php

use common\models\Journal;
use common\models\Task;
use common\models\RoomRepair;
use common\models\User;
use common\rbac\Rights;
use dosamigos\ckeditor\CKEditor;
use kartik\widgets\Select2;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\helpers\Json;
use yii\helpers\Url;

//передаем на фронт id помещения "Другое"
$otherRoomID = RoomRepair::getOtherRoomTypeId();
$globalVariableScript = "window.otherRoomID = " . $otherRoomID . ";";
$this->registerJs($globalVariableScript, yii\web\View::POS_BEGIN);
?>

<div class="row">
    <div class="col-md-12 content-container" style="padding-bottom: 0; margin-bottom: 15px;">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php if ($model->task != null):?>
            <h2>По заданию: <?= Html::a(Html::encode($model->task->name), ['task/view', 'id'=>$model->task->id], ['target'=>'_blank'])?></h2>
        <?php endif;?>
    </div>
</div>

<?php $form = ActiveForm::begin([
    'id'=>'journal-edit-form',
    'enableClientValidation'=>false,
    'enableAjaxValidation'=>false
]);?>
<div class="row">
    <div class="col-md-12 col-sm-12 content-container buy-form" style="padding-bottom: 0; margin-bottom: 15px; min-height: 606px">
        <?= Html::activeHiddenInput($model, 'version_token', []); ?>

        <?= $form->field($model, 'visibility', [])->widget(Select2::classname(), ['data' => Journal::getVisibilityList($model->task instanceof Task), 'hideSearch' => true]) ?>

        <?= $form->field($model, 'journalTypes', [])->widget(Select2::classname(), [
            'data' => \common\models\JournalType::getList(null, $model->task instanceof Task),
            'pluginOptions' => ['allowClear' => false],
            'options' => ['multiple' => true, 'placeholder'=>'Выбери тип поста', 'disabled' => ($model->task instanceof Task)],
        ]) ?>

        <div class="repair-works-container">
            <?= $form->field($model, 'repairWorks', [])->widget(Select2::classname(), [
                'data' => \common\models\WorkRepair::getList(function(\yii\db\ActiveQuery $q){$q->orderBy('(CASE WHEN name = \'Другое\' THEN 1 ELSE 0 END), name');}),
                'pluginOptions' => ['allowClear' => false],
                'options' => ['multiple' => true/*, 'placeholder'=>'Выбери работы'*/],
            ]) ?>
        </div>

        <div class="row">
            <div class="col-md-8 col-sm-8 col-xs-8">
                <?= $form->field($model, 'repairRooms', [])->widget(Select2::classname(), [
                    'data' => \common\models\RoomRepair::getList(function(\yii\db\ActiveQuery $q){$q->orderBy('(CASE WHEN name = \'Другое\' THEN 1 ELSE 0 END), name');}),
                    'pluginOptions' => ['allowClear' => false],
                    'options' => ['multiple' => true, 'placeholder'=>'Выбери помещение, в котором производился ремонт'],
                ]) ?>
            </div>

            <div class="col-md-3 col-sm-8 col-xs-8 other-room-container" style="display:none;">
                <?php if(empty($wrongOtherRoom)): ?>
                <div id="form-other-room-group" class="">
                <?php else: ?>
                <div id="form-other-room-group" class="has-error">
                <?php endif; ?>

                    <label class="control-label" for="">Другое помещение</label>
                    <?= Html::input('text', 'other-room',
                        !empty($model->journalOtherRoomType->room) ? $model->journalOtherRoomType->room : null,
                        ['class'=>'form-control', 'placeholder'=> 'Добавьте другое название помещения', 'id'=>'other-room-type']
                    )?>
                </div>

                <?php if(!empty($wrongOtherRoom)): ?>
                    <div class="has-error">
                        <p id="form-error-message" class="help-block help-block-error">Укажите дополнительное название помещения</p>
                    </div>
                <?php else: ?>
                    <div class="has-error">
                        <p id="form-error-message" style="display: none;" class="help-block help-block-error"></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>

        <div class="journal-edit-info">
            <span class="journal-edit-btn info-work" style="display: none">
                <?= Html::a(Html::icon('question-sign white').'&nbsp;'.'О чём писать?', '#', [
                    'class' => 'text-success info-modal-btn', 'data-target'=>'#info-modal-work',
                ]) ?>
            </span>
        </div>

        <div class="buy-edit" style="display: none">
            <?= $this->render('__formBuy', ['model'=>$model, 'form'=>$form]) ?>
        </div>


        <?= $form->field($model, 'content')->widget(CKEditor::className(), [
            'options' => ['rows' => 6],
            'preset' => 'full',
            'clientOptions' => ['skin' => 'bootstrapck']
        ]) ?>

    </div>
</div>

<?= $this->render('__formPhotos', ['model'=>$model, 'form'=>$form]) ?>

    <div class="row buy-list-warning hidden">
        <div class="col-md-12 content-buy-check">
            <div class="buy-list-item">
                <div class="buy-list-icon">
                    <div class="icon-warning" style=""></div>
                </div>
                <div class="buy-list-text-warning">
                    К публикации <span class="colortext-warning">не принимаются</span> посты, посвященные какой-то одной небольшой покупке (до 1000 рублей).
                    <br>К публикации <span class="colortext-warning">не принимаются</span> посты о товарах, которых нет в ассортименте Леруа Мерлен (например, кровати, телевизоры и т.д.) – полный список ассортимента представлен на официальном сайте Леруа Мерлен.
                    Вы можете покупать эти товары или их аналоги у конкурентов, но если мы совсем не продаем такую категорию товаров, пост будет отклонен.
                </div>
            </div>
        </div>
    </div>

<?= $this->render('__formGoods', ['model'=>$model, 'form'=>$form]) ?>

<div class="row">
    <div class="col-md-12 content-container" style="padding-bottom: 0;">
        <div class="form-group">
            <?= $form->field($model, 'journalTags', [])->widget(Select2::classname(), [
                'data' => \common\models\JournalTag::getList(),
                'pluginOptions' => ['allowClear' => false],
                'options' => ['multiple' => true, 'placeholder'=>'Выберите подходящие тэги'],
            ]) ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 content-container" style="padding-bottom: 0;">
        <div class="form-group">
            <?php if ($model->status == Journal::STATUS_PUBLISHED):?>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-with-margin-right', 'name' => 'save',]) ?>
            <?php else:?>
                <?= Html::submitButton('Опубликовать', ['class' => 'btn btn-primary btn-with-margin-right', 'name' => 'publish', 'data-confirm'=>'После публикации запись будет отправлена на проверку и будет недоступна для редактирования, продолжить?']) ?>
                <?= Html::submitButton('Сохранить черновик', ['class' => 'btn btn-default btn-with-margin-right',]) ?>
            <?php endif;?>
            <?= Html::submitButton('Закрыть', ['class' => 'btn btn-default', 'name' => 'cancel', 'formnovalidate' => '1',]) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
if (Yii::$app->user->can(Rights::EDIT_MY_JOURNAL, ['journal' => $model])) {
	\frontend\assets\JournalAutoSaveFormAsset::register($this);
	$this->registerJs('$("#journal-edit-form").journalAutoSaveForm('.Json::encode(['autoSaveUrl'=>Url::to(['journal/auto-save', 'id' => $model->id])]).')');
}
?>


<?php $this->registerJsFile('@web/js/journal-edit-form.min.js', ['position' => View::POS_END, 'depends'=>[\yii\web\JqueryAsset::className()]])?>
<?php /**@var User $user */
      $user = Yii::$app->user->identity;
      $this->registerJs('new JournalEditForm('. $user->flag_buy_post .');')
?>
