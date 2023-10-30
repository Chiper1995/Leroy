<?php

use common\rbac\Rights;
use yii\bootstrap\Html;
use yii\bootstrap\Tabs;

/* @var yii\web\View $this */
/* @var yii\data\ActiveDataProvider $dataProvider */
/* @var \frontend\models\dialog\MyDialogsSearch $searchModel */
/* @var bool $addingMessage */
/* @var integer $createdDialogId */
/* @var \common\models\Dialog $viewedDialog */
/* @var \frontend\models\dialog\AddMessageForm $addMessageForm */
/* @var \frontend\models\dialog\NewDialogForm $newDialogForm */

$this->title = 'Сообщения';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-12 col-md-12">
        <?php \yii\widgets\Pjax::begin(['id'=>'dialogs-pjax']); ?>
        <div class="row">
            <div class="col-md-3 col-sm-3 ">
                <h1><?= Html::encode($this->title) ?></h1>
            </div>
            <div class="col-md-9 col-sm-9">
                <div class="btn-toolbar pull-right">
                    <?php if(Yii::$app->user->can(Rights::CREATE_TICKETS, [])):?>
                        <?php if(!($addingMessage or $addingTicket or ($viewedDialog != null))):?>
                        <?= Html::a(Html::icon('plus').'&nbsp;'.'Написать Куратору', ['new-ticket'], [
                            'class' => 'btn btn-primary', 'data-pjax'=>1, 'style'=>'margin-right:20px;'
                        ]) ?>
                        <?php endif;?>
                    <?php endif;?>

                    <?php if(Yii::$app->user->can(Rights::CREATE_DIALOGS, [])):?>
                        <?php if(!($addingMessage or $addingTicket or ($viewedDialog != null))):?>
                        <?= Html::a(Html::icon('plus').'&nbsp;'.'Написать сообщение', ['new-dialog'], ['class' => 'btn btn-primary', 'data-pjax'=>1,]) ?>
                        <?php endif;?>
                    <?php endif;?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12">

                <?= Tabs::widget([
                    'items' => [
                        [
                            'label' => 'Диалоги',
                            'content' => ($addingTicket or $addingMessage or ($viewedDialog != null)) ? '' : $this->render('_dialogs', ['dataProvider'=>$dataProvider, 'searchModel'=>$searchModel]),
                            'active' => !$addingTicket and !$addingMessage and ($viewedDialog == null),
                            'options' => ['class'=>'fade in'],
                            'url' => ['index'],
                        ],
                        [
                            'label' => 'Просмотр диалогов',
                            'content' => ($viewedDialog != null) ? $this->render('_messages', ['viewedDialog'=>$viewedDialog, 'addMessageForm'=>$addMessageForm]) : '',
                            'active' => $viewedDialog != null,
                            'options' => ['class'=>'fade in'],
                            'visible' => $viewedDialog != null,
                        ],
                        [
                            'label' => 'Новое сообщение',
                            'content' => $this->render('_new_dialog', ['model'=>$newDialogForm, 'createdDialogId'=>$createdDialogId]),
                            'active' => $addingMessage,
                            'options' => ['class'=>'fade in'],
                            'visible' => $addingMessage,
                        ],
                        [
                            'label' => 'Новое сообщение куратору',
                            'content' => $this->render('_new_ticket', ['model'=>$newDialogForm, 'createdDialogId'=>$createdDialogId]),
                            'active' => $addingTicket,
                            'options' => ['class'=>'fade in'],
                            'visible' => $addingTicket,
                        ],
                    ]
                ]); ?>
            </div>
        </div>
        <?php \yii\widgets\Pjax::end(); ?>
    </div>
</div>
