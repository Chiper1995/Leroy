<?php

use common\models\Visit;
use frontend\widgets\StatusButtonsFilter\StatusButtonsFilter;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\MyVisitsSearch */

$this->title = 'Визиты ко мне';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
        <h1>Визиты ко мне</h1>
    </div>
</div>
<?php \yii\widgets\Pjax::begin(); ?>
<div class="row">
    <div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
        <?php //ANDR Доделать фильтр по статусу ?>
        <?= ''//StatusButtonsFilter::widget(['selectedStatus' => $searchModel->status, 'statusList' => Visit::getAllStatusNamesList(), 'route' => 'visit/my-visits'])?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
        <div class="visit-thumbnails">
            <?php if ($dataProvider->count > 0):?>
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '__visitItem',
                'layout' => '{items}<div class="row"><div class="col-md-12"><div class="text-center">{pager}</div></div></div>',
            ]); ?>
            <?php else:?>
            <div class="no-visits">
                <p style="font-size: 18px; padding-top: 10px;">Визитов пока не запланировано</p>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>
<?php \yii\widgets\Pjax::end(); ?>
