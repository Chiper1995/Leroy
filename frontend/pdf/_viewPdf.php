<?php

use common\models\JournalCheckPhoto;
use common\rbac\Rights;
use yii\bootstrap\Carousel;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use common\models\Journal;
use frontend\widgets\LikeLink\LikeLink;
use frontend\widgets\GiveGiftLink\GiveGiftLink;
use yii\bootstrap\ActiveForm;
use frontend\widgets\SubscribeLink\SubscribeLink;

/* @var $this yii\web\View */
/* @var $model common\models\Journal */
/* @var $displayFormPhotos bool */
/* @var $displayOnCheckPhotos bool */
/* @var $form yii\bootstrap\ActiveForm */

if (!isset($displayFormPhotos))
    $displayFormPhotos = false;

if (!isset($displayOnCheckPhotos))
    $displayOnCheckPhotos = false;

$subscribeLinkConfig = [
    'subscribedIt' => in_array($model->user_id, Yii::$app->user->identity->getSubscriptionToUserIds()),
    'url' => ['/user/subscribe-it', 'id' => $model->user_id],
]
?>

<div class="row">
    <div class="col-md-12 content-container view-journal-header" style="padding-bottom: 0; margin-bottom: 15px;">
        <?php if (Yii::$app->user->id != $model->user_id):?>
            <?php if (\Yii::$app->user->can(Rights::SHOW_FAMILY_JOURNAL, ['user'=>$model->user])):?>
                <p>
                    <?= Html::a(Html::icon('user').Html::encode($model->user->fio), ['journal/family-journal', 'id'=>$model->user->id, 'returnUrl'=>Yii::$app->request->url], ['class'=>'dark'])?>
					<?= \Yii::$app->user->can(Rights::SHOW_MY_JOURNAL_RECORDS) ? SubscribeLink::widget($subscribeLinkConfig) : '' ?>
                </p>
            <?php else:?>
                <p class="caption-with-icon">
                    <?= Html::icon('user').Html::encode($model->user->fio); ?>
                    <?= \Yii::$app->user->can(Rights::SHOW_MY_JOURNAL_RECORDS) ? SubscribeLink::widget($subscribeLinkConfig) : '' ?>
                </p>
            <?php endif;?>
        <?php endif;?>
        <h1><?= Html::encode($model->subject) ?></h1>
        <?php if ($model->task != null):?>
            <h2>По заданию: <?= Html::a(Html::encode($model->task->name), ['task/view', 'id'=>$model->task->id], ['target'=>'_blank'])?></h2>
        <?php endif;?>
        <div style="margin-bottom: 10px;">
            <b><?= $model->getAttributeLabel('journalTypes') ?>:</b> <?= (count($model->journalTypes) > 0)
                ? implode(', ', ArrayHelper::getColumn($model->journalTypes, 'name'))
                : '<span class="not-set">(не задано)</span>'; ?>
        </div>
        <?php if (count($model->repairWorks) > 0):?>
        <div style="margin-bottom: 10px;">
            <b><?= $model->getAttributeLabel('repairWorks') ?>:</b> <?= implode(', ', ArrayHelper::getColumn($model->repairWorks, 'name')); ?>
        </div>
        <?php endif;?>
    </div>
</div>

<?php $photosForCarousel = $model->status == Journal::STATUS_PUBLISHED ? $model->getPublishedPhotos() : $model->photos; ?>
<?php if (count($photosForCarousel) > 0 && $withImages):?>
<div class="row">
    <div class="col-md-12 content-container" style="padding: 0; margin-bottom: 15px;">
        <?php
        $items = [];
        foreach ($photosForCarousel as $photoIndex => $photo) {
            $items[] = Html::img($photo->getPhotoThumb(1164, 760, false, true));
        }
        ?>
        <?php
            foreach($items as $item){
                echo $item;
            }
        ?>
    </div>
</div>
<?php endif;?>

<div class="row">
    <div class="col-md-12 content-container" style="padding-bottom: 0; margin-bottom: 15px;">
        <?= $model->content ?>
    </div>
</div>

<?php if ((count($model->goods) > 0) || (count($model->checkPhotos) > 0)):?>
<div class="row">
    <div class="col-md-12 check-container-block" style="padding-bottom: 0; margin-bottom: 15px;">
        <div class="check-container">
            <div class="check-container-inner">
                <div class="check-container-inner-content">
                    <?php if (count($model->goods) > 0):?>
                    <h2>Купленные товары</h2>
                    <div id="journal-goods">
                        <?php if (count($model->goods) > 0):?>
                            <div class="row goods-view">
                                <div class="goods-header">
                                    <div class="col-md-4">
                                        Наименование
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-4">
                                                Количество
                                            </div>
                                            <div class="col-md-4">
                                                Цена, <i class="fa fa-rub"></i>
                                            </div>
                                            <div class="col-md-4">
                                                Сумма, <i class="fa fa-rub"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        Где покупались
                                    </div>
                                </div>
                            </div>
                            <?php $total = 0;?>
                            <?php foreach ($model->goods as $goodsIndex => $goods):?>
                                <div class="row goods-view">
                                    <div class="col-md-4">
                                        <?= Html::encode($goods->goods->name); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <?= Html::encode($goods->quantity); ?>
                                            </div>
                                            <div class="col-md-4">
                                                <?= Yii::$app->formatter->format(doubleval($goods->price), ['decimal', 2]) ?>
                                            </div>
                                            <div class="col-md-4">
                                                <?= Yii::$app->formatter->format(doubleval($goods->quantity*$goods->price), ['decimal', 2]) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <?= is_null($goods->goodsShop) ? '' : Html::encode($goods->goodsShop->name) ?>
                                    </div>
                                </div>
                                <?php $total += $goods->quantity*$goods->price;?>
                            <?php endforeach;?>
                            <div class="row goods-view goods-total">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-4">
                                        </div>
                                        <div class="col-md-4 text-right">
                                            Итого:
                                        </div>
                                        <div class="col-md-4">
                                            <?= Yii::$app->formatter->format(doubleval($total), ['decimal', 2]).' <i class="fa fa-rub"></i>' ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                </div>
                            </div>
                        <?php else:?>
                            <p style="text-align: center; margin-top: 25px">
                                <i>*** Товары не покупались ***</i>
                            </p>
                        <?php endif?>
                    </div>
                    <?php endif;?>
                    <?php if (count($model->checkPhotos) > 0 && $withImages):?>
                        <h2>Чеки</h2>
                        <div class="row photos" id="journal-check-photos">
                            <?php foreach ($model->checkPhotos as $photoIndex => $photo):?>
                                <?php $photoUrl = JournalCheckPhoto::getUrlPath().'/'.$photo->photo; ?>
                                <div class="col-sm-3 col-md-3 photo">
                                    <div class="thumbnail">
                                        <a class="im" rel="gallery_journal-check-photos" href="<?php echo $photoUrl ?>">
                                            <img src="<?php echo $photo->getPhotoThumb(253, 190) ?>"/>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif;?>

<?= newerton\fancybox\FancyBox::widget([
    'target' => 'a[rel=gallery_journal-check-photos]',
    'helpers' => true,
    'mouse' => true,
    'config' => [
        'maxWidth' => '90%',
        'maxHeight' => '90%',
        'playSpeed' => 7000,
        'padding' => 0,
        'fitToView' => false,
        'width' => '70%',
        'height' => '70%',
        'autoSize' => false,
        'closeClick' => false,
        'openEffect' => 'elastic',
        'closeEffect' => 'elastic',
        'prevEffect' => 'elastic',
        'nextEffect' => 'elastic',
        'closeBtn' => false,
        'openOpacity' => true,
        'helpers' => [
            'title' => ['type' => 'float'],
            'buttons' => [],
            'thumbs' => ['width' => 68, 'height' => 50],
            'overlay' => [
                'locked' => false,
                'css' => [
                    'background' => 'rgba(0, 0, 0, 0.8)'
                ]
            ]
        ],
    ]
]);
