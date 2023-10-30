<?php
/* @var yii\web\View $this */
/* @var \common\models\Dialog $viewedDialog */
/* @var \frontend\models\dialog\AddMessageForm $addMessageForm */

echo $this->render('index', [
    'searchModel' => null,
    'dataProvider' => null,
    'addingMessage' => false,
    'addingTicket' => false,
    'newDialogForm' => null,
    'createdDialogId' => null,
    'viewedDialog' => $viewedDialog,
    'addMessageForm' => $addMessageForm,
]);
