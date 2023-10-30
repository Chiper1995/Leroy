<?php
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

?>

<div class="user-view-detail">
    <div class="row">
        <div class="col-md-12">
            <p>
                <b><?= $model->getAttributeLabel('about_user')?>:</b> <?= Html::encode($model->about_user); ?>
            </p>
        </div>
    </div>
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-md-12">
            <p>
                <b><?= $model->getAttributeLabel('about_repair')?>:</b> <?= Html::encode($model->about_repair); ?>
            </p>
        </div>
    </div>
</div>

