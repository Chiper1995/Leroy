<?php
/**
* Просмотр профиля администратора
*/

use common\rbac\Rights;
use yii\bootstrap\Html;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->fio;
$this->params['breadcrumbs'][] = ['label' => $this->title];
$isCurator = Yii::$app->user->identity->curator_id == $model->id;
?>
<div class="user-view">
    <h1 class="text-center"><?= Html::encode($model->fio) ?></h1>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-4" style="margin-bottom: 75px;">
            <div class="user-view-photo">
                <?=\frontend\widgets\UserPhoto\UserPhoto::widget(['user'=>$model, 'size'=>166, 'showPosition' => false])?>
            </div>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-8 ">
            <?= Tabs::widget([
                'items' => [
                    [
                        'label' => 'Общая информация',
                        'content' => $this->render('view_mainData', ['model'=>$model]),
                        'active' => true,
                        'options' => ['class'=>'fade in']
                    ],
                    [
                        'label' => 'Личная информация',
                        'content' => $this->render('view_personalData', ['model'=>$model]),
                        'active' => false,
                        'options' => ['class'=>'fade in'],
                        'visible' => Yii::$app->user->can(Rights::SHOW_ADMINISTRATOR_FULL_INFO)
                    ],
                ]
            ]); ?>
        </div>
    </div>
    <?= Html::a('Закрыть', Url::to(Yii::$app->user->getReturnUrl()), ['class' => 'btn btn-default',]) ?>
    <?php if(Yii::$app->user->can(Rights::CREATE_TICKETS, []) && $isCurator):?>
        <?= Html::a(Html::icon('plus').'&nbsp;'.'Написать Куратору', ['dialog/new-ticket'], ['class' => 'btn btn-primary', 'data-pjax'=>1,]) ?>
    <?php endif;?>
</div>
