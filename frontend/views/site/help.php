<?php

/* @var $this yii\web\View */
/* @var $model common\models\settings\SettingsHelpPage */

$this->title = 'Справка';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <h1><?= $this->title?></h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $model->content?>
    </div>
</div>