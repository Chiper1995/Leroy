<?php
/* @var yii\web\View $this */
/* @var integer $createdDialogId */
/* @var \frontend\models\dialog\NewDialogForm $newDialogForm */

echo $this->render('index', [
    'searchModel' => null,
    'dataProvider' => null,
    'addingMessage' => false,
    'addingTicket' => true,
    'newDialogForm' => $newDialogForm,
    'createdDialogId' => $createdDialogId,
    'viewedDialog' => null,
    'addMessageForm' => null,
]);
