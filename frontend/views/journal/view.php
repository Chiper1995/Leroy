<?php

use common\models\Journal;
use common\rbac\Rights;
use frontend\widgets\JournalComments\JournalComments;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Journal */

$this->title = $model->subject;
//$this->params['breadcrumbs'][] = ['label' => 'Дневники', 'url' => ['my-journal']];
$this->params['breadcrumbs'][] = $this->title;
$displayFormPhotos = Yii::$app->user->can(Rights::EDIT_MY_JOURNAL_PHOTO, ['journal' => $model]);
$form = null;
?>

<?= $this->render('__returnReason', [
    'return_reason' => $model->return_reason,
    'displayFormPhotos' => $displayFormPhotos,
    'return_photo_reason' => $model->return_photo_reason,
]) ?>

<?php if ($displayFormPhotos): ?>
    <?php $form = ActiveForm::begin(['id' => 'journal-update-form', 'enableClientValidation'=>false, 'enableAjaxValidation'=>false]); ?>
<?php endif;?>

<?= $this->render('_view', [
    'model' => $model,
    'displayFormPhotos' => $displayFormPhotos,
    'form' => $form,
]) ?>

<?php if ($displayFormPhotos): ?>
    <?php ActiveForm::end(); ?>
<?php endif; ?>

<?php if ($model->status== Journal::STATUS_PUBLISHED):?>
    <?= JournalComments::widget(['journal' => $model]); ?>
<?php endif;?>

<div class="row">
    <div class="col-md-12 content-container" style="padding-bottom: 0;">
        <div class="content-published form-group">
            <div class="row published-text">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <?php if (Yii::$app->user->can(Rights::EDIT_JOURNAL, ['journal'=>$model])):?>
                        <?= Html::a(Html::icon('pencil').' Редактировать', ['journal/update', 'id'=>$model->id, 'returnUrl'=>Yii::$app->request->url], ['class' => 'btn btn-primary btn-with-margin-right',]) ?>
                    <?php endif;?>
                    <?php if ($displayFormPhotos): ?>
                        <?= Html::a('Сохранить', '#', ['class' => 'btn btn-primary btn-with-margin-right', 'id' => 'btn-journal-save',]) ?>

                        <?php $this->registerJs('$("#btn-journal-save").on("click", function () {$("#journal-update-form").submit(); return false;});')?>
                    <?php endif; ?>
                    <?= Html::a('Закрыть', Url::to(Yii::$app->user->getReturnUrl()), ['class' => 'btn btn-default',]) ?>
                </div>
                <div class="author-published-record col-lg-6 col-md-6 text-right" >
                    <?php if ($model->status == Journal::STATUS_PUBLISHED && Yii::$app->user->can(Rights::SHOW_ADMINISTRATOR_FULL_INFO, ['journal'=>$model])):?>
                        <?php if ($model->published_id == NULL):?>
                            <div class="color-text">Опубликовано автоматически</div>
                        <?php elseif ($model->published_id > 0):?>
                            <?= Html::a( Html::encode($model->getPublishedName()), Url::to(['user/view', 'id'=>$model->getPublishedId(), 'returnUrl'=>Yii::$app->request->url]), ['title' => 'Запись опубликована пользователем', 'data' => ['toggle' => 'tooltip', 'placement' => 'top', 'container' => 'body']]) ?>
                        <?php endif;?>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
</div>

