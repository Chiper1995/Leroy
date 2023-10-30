<?php
/** Редактирование профиля пользователя не семьи (админ, маркетинг, закупки) */

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

<div class="user-form">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-4" style="margin-bottom: 15px;">
            <?= $this->render('../_familyProfilePhoto', ['model'=>$model])?>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-8 ">
            <?= Tabs::widget([
                'items' => [
                    [
                        'label' => 'Личная информация',
                        'content' => $this->render('userProfile_personalData', ['form'=>$form, 'model'=>$model]),
                        'active' => true,
                        'options' => ['class'=>'fade in']
                    ],
                    [
                        'label' => 'Общая информация',
                        'content' => $this->render('userProfile_mainData', ['form'=>$form, 'model'=>$model]),
                        'active' => false,
                        'options' => ['class'=>'fade in']
                    ],
                ]
            ]); ?>
        </div>
    </div>

    <div class="form-group" style="margin-top: 15px; margin-bottom: 0;">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-with-margin-right']) ?>
        <?= Html::a('Отмена', Url::to(Yii::$app->user->getReturnUrl()), ['class' => 'btn btn-default',]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>