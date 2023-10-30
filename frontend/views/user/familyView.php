<?php
/**
* Просмотр профиля семьи
*/

use common\models\User;
use common\rbac\Rights;
use yii\bootstrap\Html;
use yii\bootstrap\Tabs;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->family_name;
$this->params['breadcrumbs'][] = ['label' => 'Семьи', 'url' => ['families']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="family-view">

    <h1 class="text-center"><?= Html::encode($model->family_name) ?><br/><?= Html::encode($model->fio) ?></h1>

    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-4" style="margin-bottom: 15px;">
            <div class="family-view-photo">
                <?=\frontend\widgets\UserPhoto\UserPhoto::widget(['user'=>$model, 'size'=>166, 'showPosition' => false])?>
            </div>
            <div class="family-profile-sum">
				<p><b>Вступили в проект:</b> <?= Yii::$app->formatter->format($model->created_at, 'date'); ?></p>
                <p><b>Потрачено:</b> <?= Yii::$app->formatter->format(doubleval($model->totalSpent), ['decimal', 2])?> <i class="fa fa-rub"></i></p>
                <p class="points"><b>Накоплено:</b> <?= $model->points ?> <?= Html::icon('piggy-bank') ?></i></p>
            </div>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-8 ">
            <?= Tabs::widget([
                'items' =>
					ArrayHelper::merge(
						[
							[
								'label' => 'Личная информация',
								'content' => $this->render('_familyViewPersonalData', ['model'=>$model]),
								'active' => true,
								'options' => ['class'=>'fade in']
							],
							[
								'label' => 'Информация о ремонте',
								'content' => $this->render('_familyViewRepairData', ['model'=>$model]),
								'active' => false,
								'options' => ['class'=>'fade in']
							],
							[
								'label' => 'Дополнительно',
								'content' => $this->render('_familyViewAdditionalData', ['model'=>$model]),
								'active' => false,
								'options' => ['class'=>'fade in']
							],
						],
						Yii::$app->user->can(Rights::SHOW_FAMILY_POINTS_HISTORY)
							? [
								[
									'label' => 'Баллы',
									'content' => $this->render('_familyViewPointsHistory', ['model'=>$model]),
									'active' => false,
									'options' => ['class'=>'fade in']
								],
							]
							: []
					)
            ]); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-9 col-md-9 col-sm-8">
        <?= Html::a('Перейти в дневник', Url::to(['journal/family-journal', 'id'=>$model->id,]), ['class' => 'btn btn-primary btn-with-margin-right',]) ?>
        <?= Html::a('Закрыть', Url::to(Yii::$app->user->getReturnUrl(['user/families'])), ['class' => 'btn btn-default',]) ?>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-4">
        <?php if ($model->curator instanceof User):?>
        <div class="curator-ava">
            <?= Html::a(
                    \frontend\widgets\UserPhoto\UserPhoto::widget(['user'=>$model->curator, 'size'=>70]),
                    Url::to(['user/view', 'id'=>$model->curator->id, 'returnUrl'=>Yii::$app->request->url]),
                    ['title' => 'Куратор: '.Html::encode($model->curator->fio), 'data' => ['toggle' => 'tooltip', 'placement' => 'top', 'container' => 'body']]
            ) ?>
        </div>
        <?php endif;?>
    </div>
</div>
