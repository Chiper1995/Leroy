<?php
/** Редактирование профиля пользователя семьи */

use common\models\User;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = 'Ваш профиль';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="family-profile">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'family-profile-form']); ?>

    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-4" style="margin-bottom: 15px;">
            <?= $this->render('_familyProfilePhoto', ['model'=>$model])?>
            <div class="family-profile-sum">
				<p><b>Вступили в проект:</b> <?= Yii::$app->formatter->format($model->created_at, 'date'); ?></p>
                <p><b>Потрачено:</b> <?= Yii::$app->formatter->format(doubleval($model->totalSpent), ['decimal', 2])?> <i class="fa fa-rub"></i></p>
                <p class="points"><b>Накоплено:</b> <?= $model->points ?> <?= Html::icon('piggy-bank') ?></i></p>
            </div>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-8 ">
            <?= Tabs::widget([
                'items' => [
                    [
                        'label' => 'Личная информация',
                        'content' => $this->render('_familyProfilePersonalData', [
                            'form'=>$form, 'model'=>$model, 'formProfile' => $formProfile,
                        ]),
                        'active' => true,
                        'options' => ['class'=>'fade in']
                    ],
                    [
                        'label' => 'Дополнительно',
                        'content' => $this->render('_familyProfileAdditionalData', ['form'=>$form, 'model'=>$model]),
                        'active' => false,
                        'options' => ['class'=>'fade in']
                    ],
                ]
            ]); ?>
        </div>
    </div>
    <div class="row" style="margin-top: 6px;">
        <div class="col-lg-9 col-md-9 col-sm-8">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-with-margin-right']) ?>
            <?= Html::a('Отмена', Url::to(Yii::$app->user->getReturnUrl()), ['class' => 'btn btn-default',]) ?>
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

    <?php ActiveForm::end(); ?>
</div>

<?php $this->registerJs('
    function highLightErrorTab() {
        var _activated = false;
        $(\'.nav-tabs>li>a\').each(function(){
            if ($($(this).attr(\'href\')).find(\'.has-error\').length > 0) {
                $(this).addClass(\'tab-has-error\');
                if (!_activated) {
                    $(this).tab(\'show\');
                    _activated = true;
                }
            }
        });
    }
    highLightErrorTab();

    $(\'#family-profile-form\').on(\'afterValidate\', function() {
		highLightErrorTab();
	});
', \yii\web\View::POS_READY); ?>
