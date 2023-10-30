<?php
/* @var yii\web\View $this */
/* @var yii\data\ActiveDataProvider $dataProvider */
/* @var \frontend\models\dialog\MyDialogsSearch $searchModel */

echo $this->render('index', [
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider,
    'addingMessage' => false,
    'addingTicket' => false,
    'newDialogForm' => null,
    'createdDialogId' => null,
    'viewedDialog' => null,
    'addMessageForm' => null,
]);
