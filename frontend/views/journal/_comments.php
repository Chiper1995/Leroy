<?php

use dosamigos\ckeditor\CKEditor;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $journal common\models\Journal */
/* @var $model common\models\JournalComment */
?>

<div class="row">
    <div class="col-md-12 content-container" style="margin-bottom: 15px;">
        <?php Pjax::begin(['id'=>'comments']); ?>
        <?= Html::a("Refresh", ['journal/view', 'id'=>$journal->id], ['class' => 'btn btn-lg btn-primary']);?>
        <h1>Current time: <?= time() ?></h1>

        <div class="comment-list">
            <h2>Комментарии</h2>
            <?php echo time()?>
            <?php if ($journal->getComments()->count() == 0) :?>
                <div>
                    Пока никто не оставлял комментариев, ты можешь стать первым!
                </div>
            <?php else:?>

            <?php endif;?>
        </div>
        <div class="comment-form">
            <?php $form = ActiveForm::begin(['id'=>'comment-form', 'options'=>['data-pjax' => true]]); ?>
            <?= $form->field($model, 'content')->textarea(['rows'=>5])?>
            <?= Html::activeHiddenInput($model, 'parent_id', ['id'=>'comment_parent_id'])?>
            <div style="margin-top: 15px;">
                <?= Html::submitButton(Html::icon('comment').'&nbsp;Добавить комментарий', ['name' => 'add_comment', 'class' => 'btn btn-primary btn-with-margin-right', 'data-pjax' => true]) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <?php Pjax::end(); ?>
    </div>
</div>