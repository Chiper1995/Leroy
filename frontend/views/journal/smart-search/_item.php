<?php

use common\models\Journal;
use common\rbac\Rights;
use yii\bootstrap\Html;
use yii\helpers\Url;
use frontend\models\journal\AllJournalSmartSearch;

/* @var $this yii\web\View */
/* @var array $model */

$journal = \common\models\Journal::find()
	->joinWith(['user'], true)
	->with('photos')
	->where(['{{%journal}}.id' => $model['id']])
	->one();
?>

<div class="col-md-12">
	<div class="row">
		<div class="col-md-3">
			<a href="<?= Url::to(['journal/view', 'id'=>$journal->id, 'returnUrl'=>Yii::$app->request->url])?>">
				<?php $photosForThumb = $journal->status == Journal::STATUS_PUBLISHED ? $journal->getPublishedPhotos() : $journal->photos; ?>
				<?php if (count($photosForThumb) > 0) {
					echo Html::img($photosForThumb[0]->getPhotoThumb(360, 270));
				}
				else {
					echo Html::img('files/no_photo.gif');
				}
				?>
			</a>
		</div>
		<div class="col-md-9">
			<h2><a href="<?= Url::to(['journal/view', 'id' => $journal->id, 'returnUrl' => Yii::$app->request->url]); ?>">
			<?php  if (array_key_exists('subject_snippet', $model) && is_string($model['subject_snippet'])) echo $model['subject_snippet']; ?></a></h2>

			<?php if (\Yii::$app->user->can(Rights::SHOW_FAMILIES)):?>
				<p>
					<?= Html::a(Html::icon('user'). Html::encode($journal->user->fio), ['user/family-view', 'id' => $journal->user->id,], ['class' => 'dark']); ?>
					<span class="date"><?= Html::icon('calendar') . '&nbsp;' . Html::encode(Yii::$app->formatter->asDate($journal->updated_at)); ?></span>
				</p>
			<?php else:?>
				<p>
					<?= Html::a(Html::icon('user'). Html::encode($journal->user->fio), ['journal/family-journal', 'id' => $journal->user->id,], ['class' => 'dark']); ?>
					<span class="date"><?= Html::icon('calendar') . '&nbsp;' . Html::encode(Yii::$app->formatter->asDate($journal->updated_at)); ?></span>
				</p>
			<?php endif;?>

			<?= AllJournalSmartSearch::prepareSnippet($model['content_snippet_1'], $model['content_snippet_2']); ?>
                        <?php if (array_key_exists('preparation_purchase_snippet', $model) && is_string($model['preparation_purchase_snippet']))
echo  $model['preparation_purchase_snippet'] ?>
			<?php
if (array_key_exists('store_selection_snippet', $model) && is_string($model['store_selection_snippet']))
echo $model['store_selection_snippet'] ?>
			<?php
if (array_key_exists('assessment_product_snippet', $model) && is_string($model['assessment_product_snippet']))
echo $model['assessment_product_snippet'] ?>
			<?php
if (array_key_exists('conclusion_snippet', $model) && is_string($model['conclusion_snippet']))
echo $model['conclusion_snippet'] ?>
			<?php
if (array_key_exists('advice_snippet', $model) && is_string($model['advice_snippet']))
echo $model['advice_snippet'] ?>
		</div>
	</div>
</div>
