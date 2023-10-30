<?php
use yii\bootstrap\Html;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model common\models\Journal */

$this->title = 'Редактирование записи';
$this->params['breadcrumbs'][] = ['label' => 'Мои записи', 'url' => ['my-journal']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('__returnReason', [
    'return_reason' => $model->return_reason,
    'displayFormPhotos' => (!empty($displayFormPhotos)) ? $displayFormPhotos : '',
    'return_photo_reason' => $model->return_photo_reason,
]) ?>

<?= $this->render('_form', [
    'model' => $model,
    'wrongOtherRoom' => isset($wrongOtherRoom) ? $wrongOtherRoom : false,
]) ?>

