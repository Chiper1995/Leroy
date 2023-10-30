<?php
use common\rbac\Rights;
use yii\bootstrap\Html;

/**@var \common\models\User $model*/
?>

<a class="thumbnail"
   <?php if (\Yii::$app->user->can(Rights::SHOW_FAMILY_JOURNAL, ['user'=>$model])):?>href="<?=\yii\helpers\Url::to(['journal/family-journal', 'id'=>$model->id,])?>"<?php endif;?>>
	<div class="im">
		<?= \frontend\widgets\UserPhoto\UserPhoto::widget(['user' => $model, 'size' => 166]) ?>
	</div>
	<div class="memo">
		<h1 class="text-underline">
			<?= Html::encode($model->family_name); ?>
		</h1>
		<p><b><?= Html::encode($model->fio); ?></b></p>
		<p><b><?= implode(',<br/>', \yii\helpers\ArrayHelper::getColumn($model->cities, 'name')); ?></b></p>
		<p><b>Вступили в проект:</b> <?= Yii::$app->formatter->format($model->created_at, 'date'); ?></p>
	</div>
</a>
