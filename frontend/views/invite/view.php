<?php
use common\models\Invite;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model Invite */

$this->title = 'Анкета #' . $model->id;
$this->params['breadcrumbs'][] = $this->title;

$empty = '<span class="empty-value">(не указано)</span>';
?>
<div>
	<div class="row">
		<div class="col-sm-12">
			<h1><?= Html::encode($this->title) ?></h1>
		</div>
	</div>
	<div class="row" style="margin-top: 15px;">
		<div class="col-sm-12 invite-detail-view">
			<div><?php echo Html::activeLabel($model, 'sex'); ?></div>
			<div><?php echo ArrayHelper::getValue(Invite::$L_SEX, $model->sex, $empty); ?></div>

			<div><?php echo Html::activeLabel($model, 'age'); ?></div>
			<div><?php echo $model->age ? $model->age : $empty; ?></div>

			<div><?php echo Html::activeLabel($model, 'city_id'); ?></div>
			<div><?php echo $model->city !== null ? $model->city->name : ($model->city_other ? $model->city_other : $empty); ?></div>

			<div><?php echo Html::activeLabel($model, 'family'); ?></div>
			<div><?php echo ArrayHelper::getValue(Invite::$L_FAMILY, $model->family, $empty); ?></div>

			<div><?php echo Html::activeLabel($model, 'children'); ?></div>
			<div><?php echo ArrayHelper::getValue(Invite::$L_HAVE_CHILDREN, $model->children, $empty); ?></div>

			<div><?php echo Html::activeLabel($model, 'repair_status'); ?></div>
			<div><?php echo ArrayHelper::getValue(Invite::$L_REPAIR_STATUS, $model->repair_status, $empty); ?></div>

			<div><?php echo Html::activeLabel($model, 'repair_when_finish'); ?></div>
			<div><?php echo ArrayHelper::getValue(Invite::$L_REPAIR_WHEN_FINISH, $model->repair_when_finish, $empty); ?></div>

			<div><?php echo Html::activeLabel($model, 'typeOfRepair'); ?></div>
			<div><?php echo count($model->typeOfRepair) > 0 ? implode('<br/>', array_map(function($v){return '- ' . $v;}, $model->typeOfRepair)) : $empty; ?></div>

			<div><?php echo Html::activeLabel($model, 'repairObject'); ?></div>
			<div><?php echo count($t = $model->getRepairObjectsText()) > 0 ? implode('<br/>', array_map(function($v){return '- ' . $v;}, $t)) : $empty; ?></div>

			<div><?php echo Html::activeLabel($model, 'have_cottage'); ?></div>
			<div><?php echo ArrayHelper::getValue(Invite::$L_HAVE_COTTAGE, $model->have_cottage, $empty); ?></div>

			<div><?php echo Html::activeLabel($model, 'plan_cottage_works'); ?></div>
			<div><?php echo ArrayHelper::getValue(Invite::$L_PLAN_COTTAGE_WORKS, $model->plan_cottage_works, $empty); ?></div>

			<div><?php echo Html::activeLabel($model, 'who_worker'); ?></div>
			<div><?php echo ArrayHelper::getValue(Invite::$L_WHO_WORKER, $model->who_worker, $empty); ?></div>

			<div><?php echo Html::activeLabel($model, 'who_chooser'); ?></div>
			<div><?php echo ArrayHelper::getValue(Invite::$L_WHO_CHOOSER, $model->who_chooser, $empty); ?></div>

			<div><?php echo Html::activeLabel($model, 'who_buyer'); ?></div>
			<div><?php echo ArrayHelper::getValue(Invite::$L_WHO_BUYER, $model->who_buyer, $empty); ?></div>

			<div><?php echo Html::activeLabel($model, 'shop_name'); ?></div>
			<div><?php echo $model->shop_name ? $model->shop_name : $empty; ?></div>

			<div><?php echo Html::activeLabel($model, 'distance'); ?></div>
			<div><?php echo ArrayHelper::getValue(Invite::$L_DISTANCE, $model->distance, $empty); ?></div>

			<div><?php echo Html::activeLabel($model, 'money'); ?></div>
			<div><?php echo ArrayHelper::getValue(Invite::$L_MONEY, $model->money, $empty); ?></div>

			<div><?php echo Html::activeLabel($model, 'fio'); ?></div>
			<div><?php echo $model->fio ? $model->fio : $empty; ?></div>

			<div><?php echo Html::activeLabel($model, 'phone'); ?></div>
			<div><?php echo $model->phone ? $model->phone : $empty; ?></div>

			<div><?php echo Html::activeLabel($model, 'email'); ?></div>
			<div><?php echo $model->email ? $model->email : $empty; ?></div>

			<div><label>Ссылка для регистрации:</label></div>
			<div><?php echo Html::a(
					Url::to(['registration/index', 'id' => $model->session_id], true),
					['registration/index', 'id' => $model->session_id],
					['target' => '_blank']
				); ?></div>

			<div class="form-group" style="margin-top: 20px; padding-top: 10px; border-top: solid 1px #ccc;">
				<?= Html::a('Закрыть', Yii::$app->user->getReturnUrl('list'), ['class' => 'btn btn-default']) ?>
			</div>
		</div>
	</div>
</div>