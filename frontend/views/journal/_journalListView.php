<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $viewOnly bool */
/* @var $showAuthor bool */
/* @var $removeFavorite bool */

use common\components\ScrollPager;
use yii\widgets\ListView;
?>

<div class="journal-thumbnails">
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '__journalItem',
        'emptyText' => false,
        'itemOptions' => ['class' => 'col-md-4 col-sm-6 item'],
        'viewParams' => ['viewOnly' => $viewOnly, 'showAuthor' => $showAuthor, 'removeFavorite' => isset($removeFavorite) ? $removeFavorite : false],
        'layout' => '<div class="row"><div class="col-md-12">{items}</div></div><div class="row"><div class="col-md-12"><div class="text-center">{pager}</div></div></div>',
        'pager' => [
            'class' => ScrollPager::className(),
            'spinnerSrc' => '/css/img/loading.gif',
            'triggerText' => 'Показать ещё',
            'triggerTemplate' => '<div class="ias-trigger" style="text-align:center;cursor:pointer;"><a class="btn btn-primary">{text} <span class="glyphicon glyphicon-chevron-down"></span></a></div>',
            'noneLeftText' => '',
            'triggerOffset' => 5,
            'historyPrev' => '.prev a',
            'triggerTextPrev' => 'Показать ещё',
            'triggerTemplatePrev' => '<div class="ias-trigger" style="text-align:center;cursor:pointer;"><a class="btn btn-primary">{text} <span class="glyphicon glyphicon-chevron-up"></span></a></div>',
            'enabledExtensions' => [
                ScrollPager::EXTENSION_TRIGGER,
                ScrollPager::EXTENSION_SPINNER,
                //ScrollPager::EXTENSION_NONE_LEFT,
                ScrollPager::EXTENSION_PAGING,
                ScrollPager::EXTENSION_HISTORY
            ],
			'addControlsToParent' => true,
        ]
    ]); ?>
</div>
