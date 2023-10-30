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
        <?php Pjax::end(); ?>
    </div>
</div>