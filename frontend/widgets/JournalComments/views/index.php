<?php

use common\rbac\Rights;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\web\View;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $journal common\models\Journal */
/* @var $model common\models\JournalComment */
/* @var $createdCommentId integer */
?>

<?php $this->registerJsFile('@web/js/journal-comments.min.js', ['position' => View::POS_END, 'depends'=>\yii\web\JqueryAsset::className()])?>

<?php
/**
 * @param common\models\JournalComment $parent
 * @param yii\web\View $view
 * @param integer $createdCommentId
 * @return string
 */
function printChildren($parent, $view, $createdCommentId) {
    $result = '';
    foreach ($parent->children as $comment) {
        $result .= $view->render('_comment', [
			'comment'=>$comment,
			'childrenCommentsHtml' => printChildren($comment, $view, $createdCommentId),
			'tag'=>'div',
			'isAdded'=>$createdCommentId == $comment->id
		]);
    }
    return $result;
}
?>

<div class="row">
    <div class="col-md-12 content-container" style="margin-bottom: 15px;">
        <?php Pjax::begin(); ?>
        <div class="comment-list">
            <h2>Комментарии</h2>
            <?php if ($journal->getComments()->count() == 0) :?>
                <div>
                    Пока никто не оставлял комментариев<?php if (Yii::$app->user->can(Rights::ADD_COMMENT, ['journal' => $journal])) : ?>, ты можешь стать первым!<?php else: ?>.<?php endif; ?>
                </div>
            <?php else:?>
                <ul class="media-list">
                    <?php /**@var \common\models\JournalComment $comment*/ ?>
                <?php foreach ($journal->getComments()->andWhere(['parent_id'=>null])->orderBy(['updated_at'=>SORT_ASC])->all() as $comment):?>
                    <?= $this->render('_comment', ['comment'=>$comment, 'childrenCommentsHtml'=>printChildren($comment, $this, $createdCommentId), 'tag'=>'li', 'isAdded'=>$createdCommentId == $comment->id]);?>
                <?php endforeach;?>
                </ul>
            <?php endif;?>
        </div>
		<?php if (Yii::$app->user->can(Rights::ADD_COMMENT, ['journal' => $journal])) : ?>
        <div class="comment-form">
            <?php $form = ActiveForm::begin(['id'=>'comment-form', 'enableAjaxValidation' => false, 'options'=>['data-pjax' => '0']]); ?>

            <?= Html::activeLabel($model, 'content')?>
            <div class="parent-comment">
                <b>Ответ на комментарий:</b>
                <div></div>
                <?= Html::a(Html::icon('remove').'&nbsp;Отменить', '#', ['class'=>'cancel-reply', 'style'=>'font-weight: bold'])?>
            </div>
            <?= $form->field($model, 'content')->textarea(['rows'=>5, 'id'=>'comment_content'])->label(false)?>
            <?= Html::activeHiddenInput($model, 'parent_id', ['id'=>'comment_parent_id'])?>
            <div style="margin-top: 15px;">
                <?= Html::submitButton(Html::icon('comment').'&nbsp;Добавить комментарий', ['name' => 'add_comment', 'id'=>'add-comment', 'class' => 'btn btn-primary btn-with-margin-right']) ?>
            </div>
            <?php ActiveForm::end(); ?>

            <?php $form = ActiveForm::begin(['id'=>'edit-comment-form', 'enableAjaxValidation' => false, 'options'=>['data-pjax' => '0']]); ?>
            <?= Html::hiddenInput('comment_text') ?>
            <?= Html::submitButton('', ['name' => 'edit_comment', 'id'=>'edit-comment', 'class' => 'hidden']) ?>
            <?php ActiveForm::end(); ?>

            <?php $form = ActiveForm::begin(['id'=>'del-comment-form', 'enableAjaxValidation' => false, 'options'=>['data-pjax' => '0']]); ?>
            <?= Html::hiddenInput('comment_id') ?>
            <?= Html::submitButton('', ['name' => 'del_comment', 'id'=>'del-comment', 'class' => 'hidden']) ?>
            <?php ActiveForm::end(); ?>
        </div>
		<?php endif; ?>
        <?php
        if (intval($model->parent_id)>0)
            $this->registerJs('setParentComment($(".reply[data-id='.$model->parent_id.']"));');

        $this->registerJs('if (!isScrolledIntoView(".media.new")) $("html, body").animate({scrollTop: $(".media.new").offset().top - 100}, 100); setTimeout(function(){$(".media.new").removeClass("new");}, 500);');
        ?>
        <?php Pjax::end(); ?>
    </div>
</div>
<?php $this->registerJs(
    <<<JS
       $('body').on('click', '#add-comment', function() {
            if($('#comment_content').val() !== ""){
               $('#add-comment').hide();
            }
        });
JS
    , \yii\web\View::POS_LOAD);?>
