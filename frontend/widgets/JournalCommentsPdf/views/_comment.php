<?php

use common\models\JournalComment;
use common\rbac\Rights;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $comment JournalComment */
/* @var $childrenCommentsHtml string */
/* @var $tag string */
/* @var $isAdded boolean */
?>

<<?=$tag?> class="media<?=($isAdded ? ' new' : '')?>" id="comment-<?=$comment->id?>">
    <div class="media-left">
        <span>
            <?=\frontend\widgets\UserPhoto\UserPhoto::widget(['user'=>$comment->user, 'size'=>70])?>
        </span>
    </div>
    <div class="media-body">
        <h4 class="media-heading">
            <span><?php if (\Yii::$app->user->can(Rights::SHOW_FAMILY_JOURNAL, ['user'=>$comment->user])):?>
					<?= Html::a(Html::encode($comment->user->fio), ['journal/family-journal', 'id'=>$comment->user->id,], ['class' => 'username']); ?>
				<?php else:?>
					<?= Html::encode($comment->user->fio); ?>
				<?php endif;?></span><?php
			if (Yii::$app->user->can(Rights::ADD_COMMENT, ['journal' => $comment->journal])) {
				echo Html::a(Html::icon('share-alt') . '&nbsp;Ответить', '#add-comment', ['data-id' => $comment->id, 'class' => 'reply']);
			}

			if (Yii::$app->user->can(Rights::DELETE_COMMENT, ['comment' => $comment])) {
				echo Html::a(Html::icon('remove') . '&nbsp;Удалить', '#', ['data-id' => $comment->id, 'class' => 'delete']);
			}
            ?>
        </h4>
        <div class="datetime">
			<?= Yii::$app->formatter->format($comment->updated_at, 'datetime')?>
			<?php if ($comment->parent instanceof JournalComment):?>
			<b> ответ для <?= Html::encode($comment->parent->user->fio); ?></b>
			<?php endif; ?>
		</div>
        <p><?= Html::encode($comment->content); ?></p>
    </div>
</<?=$tag?>>
<<?=$tag?> class="reply-comment">
	<?= $childrenCommentsHtml ?>
</<?=$tag?>>
