<?php

use common\models\Journal;
use common\rbac\Rights;
use frontend\widgets\JournalCommentsPdf\JournalCommentsPdf;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Journal */
/* @var $context \yii2tech\html2pdf\Template */
$context = $this->context;

$context->layout = 'layouts/main';

$context->pdfOptions = [
    'pageSize' => 'A4',
];
$displayFormPhotos = Yii::$app->user->can(Rights::EDIT_MY_JOURNAL_PHOTO, ['journal' => $model]);
$form = null;

echo $this->render('_viewPdf', [
    'model' => $model,
    'displayFormPhotos' => $displayFormPhotos,
    'form' => $form,
    'withImages' => $withImages,
]);
