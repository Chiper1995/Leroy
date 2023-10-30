<?php

use common\models\JournalCheckPhoto;
use common\rbac\Rights;
use frontend\widgets\FavoriteLink\FavoriteLink;
use yii\bootstrap\Carousel;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use common\models\Journal;
use frontend\widgets\LikeLink\LikeLink;
use frontend\widgets\GiveGiftLink\GiveGiftLink;
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
];

$isPurchase = in_array(2, ArrayHelper::getColumn($model->journalTypes, 'id')) && count(ArrayHelper::getColumn($model->journalTypes, 'id'))==1;
?>

<div class="row">
    <div class="col-md-12 content-container view-journal-header" style="padding-bottom: 0; margin-bottom: 15px;">
        <?php if (Yii::$app->user->id != $model->user_id):?>
            <?php if (\Yii::$app->user->can(Rights::SHOW_FAMILY_JOURNAL, ['user'=>$model->user])):?>
                <p>
                    <?= Html::a(Html::icon('user').Html::encode($model->user->fio), ['journal/family-journal', 'id'=>$model->user->id, 'returnUrl'=>Yii::$app->request->url], ['class'=>'dark'])?>
                    <?= \Yii::$app->user->can(Rights::SHOW_MY_JOURNAL_RECORDS) || \Yii::$app->user->can(Rights::SHOW_MY_JOURNAL_FAVORITES) ? SubscribeLink::widget($subscribeLinkConfig) : '' ?>
                </p>
            <?php else:?>
                <p class="caption-with-icon">
                    <?= Html::icon('user').Html::encode($model->user->fio); ?>
                    <?= \Yii::$app->user->can(Rights::SHOW_MY_JOURNAL_RECORDS) || \Yii::$app->user->can(Rights::SHOW_MY_JOURNAL_FAVORITES)  ? SubscribeLink::widget($subscribeLinkConfig) : '' ?>
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

        <?php if (count($model->repairRooms) > 0):?>
        <div style="margin-bottom: 10px;">
            <b><?= $model->getAttributeLabel('repairRooms') ?>:</b> <?= $model->getRoomsMessage(); ?>
        </div>
        <?php endif;?>

        <?php if (($model->status == \common\models\Journal::STATUS_PUBLISHED) && ($model->points > 0 || $model->getGiftPoints() > 0)):?>
        <div class="points">
            <?= Html::icon('piggy-bank') ?>
            <p class="value"><?= $model->points ?><?= $model->getGiftPoints()>0 ? '+'.$model->getGiftPoints() : '' ?></p>
            <p class="caption">баллов</p>
        </div>
        <?php //ANDR: Сделать правильную форму слова ?>
        <?php endif;?>
    </div>
</div>

<?php $photosForCarousel = $model->status == Journal::STATUS_PUBLISHED ? $model->getPublishedPhotos() : $model->photos; ?>
<?php if (count($photosForCarousel) > 0):?>
<div class="row">
    <div class="col-md-12 content-container" style="padding: 0; margin-bottom: 15px;">
        <?php
        $items = [];
        foreach ($photosForCarousel as $photoIndex => $photo) {
            $items[] = $this->render('carousel-item', ['model' => $photo]);
        }
        ?>
        <?= Carousel::widget([
            'items' => $items,
            'controls' => [
                '<span class="glyphicon glyphicon-chevron-left" ></span>',
                '<span class="glyphicon glyphicon-chevron-right" ></span>'
            ],
            'options' => ['class'=>'slide', 'data-rider'=>'carousel', 'id'=>'carousel-example-captions'],
        ]); ?>
        <div class="new-caption-area content-description carousel-control-description "></div>
    </div>
</div>
<?php endif;?>

<?php $this->registerJs(<<<'JS'
    jQuery(function ($) {
        $('.carousel').carousel();
        var caption = $('div.item:nth-child(1) .carousel-caption');
        $('.new-caption-area').html(caption.html());
        caption.css('display', 'none');
        
        var textNode = $('.new-caption-area .text');
        if (textNode[0].clientHeight >= textNode[0].scrollHeight){
            $('.new-caption-area .cursor-pointer').addClass('hidden');
        } else {
            $('.new-caption-area .cursor-pointer').removeClass('hidden');
        }

        $(".carousel").on('slide.bs.carousel', function (evt) {
            var caption = $('div.item:nth-child(' + ($(evt.relatedTarget).index() + 1) + ') .carousel-caption');
            $('.new-caption-area').html(caption.html());
            caption.css('display', 'none');
            
            var textNode = $('.new-caption-area .text');
            if (textNode[0].clientHeight >= textNode[0].scrollHeight){
                $('.new-caption-area .cursor-pointer').addClass('hidden');
            } else {
                $('.new-caption-area .cursor-pointer').removeClass('hidden');
            }
        });
    });
JS
);?>

<?php if ($displayOnCheckPhotos): ?>
    <?php if (count($model->getOnCheckPhotos()) > 0):?>
        <div class="row">
            <div class="col-md-12 content-container" style="padding: 0; margin-bottom: 15px;">
                <div class="content-container-caption" style="padding: 15px 15px 0 15px">
                    <h2>Фотографии на проверку</h2>
                </div>
                <?php
                $items = [];
                foreach ($model->getOnCheckPhotos() as $photoIndex => $photo) {
                    $items[] = Html::img($photo->getPhotoThumb(1164, 760, false, true));
                }
                ?>
                <?= Carousel::widget([
                    'items' => $items,
                    'controls' => [
                        '<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span class="sr-only">Предыдущая</span>',
                        '<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span><span class="sr-only">Следующая</span>'
                    ],
                    'options' => ['class'=>'slide'],
                ]); ?>
            </div>
        </div>
    <?php endif;?>
<?php endif; ?>

<?php if ($displayFormPhotos): ?>
    <?= $this->render('__formPhotos', ['model'=>$model, 'form'=>$form, 'photos' => $model->getUnpublishedPhotos()]) ?>
<?php endif; ?>

<div class="row">
    <div class="col-md-12 content-container buy-form">
        <?php if ($isPurchase): ?>
            <?php if ($model->updated_at > 1593079200): ?>
                <?php if (Yii::$app->user->identity->role != 'user'): ?>
                    <div class="buy-list-item">
                        <div class="buy-list-icon">
                            <div class="icon-preparation-for-purchase" style=""></div>
                            <?php if (Yii::$app->request->pathInfo == "journal/check"): ?>
                                <div class="text-content">
                                    <label class="control-label">Подготовка к покупке</label>
                                    <br><span class="colortext">Если речь идет о функциональном товаре:</span> Как Вы поняли какой именно товар Вам нужен? Какие источники информации использовали для понимания?
                                    <br><span class="colortext">Если речь идет о декоративном товаре:</span> Какими источниками вдохновлялись при выборе товара? С какими предметами интерьера товар должен <br>сочетаться/контрастировать по цвету или стилю? Расскажите про декоративную задумку.
                                </div>
                            <?php else:?>
                                <div class="label-text">
                                    <label class="control-label">Подготовка к покупке</label>
                                </div>
                            <?php endif;?>
                        </div>
                        <div class="buy-list-content">
                            <div class="buy-text-content"><?= $model->preparation_purchase;?></div>
                        </div>
                    </div>
                    <div class="buy-list-item">
                        <div class="buy-list-icon">
                            <div class="icon-store-selection" style=""></div>
                            <?php if (Yii::$app->request->pathInfo == "journal/check"): ?>
                                <div class="text-content">
                                    <label class="control-label">Выбор магазина</label>
                                    <br>Вы смотрели этот товар в других магазинах или на сайтах других магазинов? Если да, расскажите: в каких ещё магазинах смотрели, что понравилось в этих магазинах, а что нет.
                                    Почему? Что послужило ключевым фактором при принятии решения о выборе магазина для покупки?
                                </div>
                            <?php else:?>
                                <div class="label-text">
                                    <label class="control-label">Выбор магазина</label>
                                </div>
                            <?php endif;?>
                        </div>
                        <div class="buy-list-content">
                            <div class="buy-text-content"><?= $model->store_selection;?></div>
                        </div>
                    </div>
                    <div class="buy-list-item">
                        <div class="buy-list-icon">
                            <div class="icon-assessment-product" style=""></div>
                            <?php if (Yii::$app->request->pathInfo == "journal/check"): ?>
                                <div class="text-content">
                                    <label class="control-label">Оценка выкладки товара</label>
                                    <br>Насколько удобно Вам было найти и выбрать нужный товар в магазине/на сайте? Обращались ли Вы к продавцу-консультанту за помощью в поиске и выборе товара?
                                    <br>Как можно улучшить выкладку товара/страницу сайта, чтобы было удобнее выбирать?
                                </div>
                            <?php else:?>
                                <div class="label-text">
                                    <label class="control-label">Оценка выкладки товара</label>
                                </div>
                            <?php endif;?>
                        </div>
                        <div class="buy-list-content">
                            <div class="buy-text-content"><?= $model->assessment_product;?></div>
                        </div>
                    </div>
                    <div class="buy-list-item">
                        <div class="buy-list-icon">
                            <div class="icon-conclusion" style=""></div>
                            <?php if (Yii::$app->request->pathInfo == "journal/check"): ?>
                                <div class="text-content">
                                    <label class="control-label">Заключение</label>
                                    <br><span class="colortext">Если речь идет о функциональном товаре:</span> Удобен ли товар в использовании? Насколько Вы довольны покупкой?
                                    <br><span class="colortext">Если речь идет о декоративном товаре:</span> Как товар выглядит в интерьере? Насколько Вы довольны покупкой?
                                </div>
                            <?php else:?>
                                <div class="label-text">
                                    <label class="control-label">Заключение</label>
                                </div>
                            <?php endif;?>
                        </div>
                        <div class="buy-list-content">
                            <div class="buy-text-content"><?= $model->conclusion;?></div>
                        </div>
                    </div>
                    <?php if ($model->advice != NULL || $model->advice != ""):?>
                    <div class="buy-list-item">
                        <div class="buy-list-icon">
                            <div class="icon-advice" style=""></div>
                            <?php if (Yii::$app->request->pathInfo == "journal/check"): ?>
                                <div class="text-content">
                                    <label class="control-label">Советы другим</label>
                                    <br>Что можете порекомендовать новичкам, которым только предстоит выбор такого товара?
                                </div>
                            <?php else:?>
                                <div class="label-text">
                                    <label class="control-label">Советы другим</label>
                                </div>
                            <?php endif;?>
                        </div>
                        <div class="buy-list-content">
                            <div class="buy-text-content"><?= $model->advice;?></div>
                        </div>
                    </div>
                    <?php endif;?>
                    <?php if ($model->additional_information != NULL || $model->additional_information != ""):?>
                    <div class="buy-list-item">
                        <div class="buy-list-icon">
                            <div class="icon-additional-information" style=""></div>
                            <?php if (Yii::$app->request->pathInfo == "journal/check"): ?>
                                <div class="text-content">
                                    <label class="control-label">Дополнительная информация</label>
                                    <br>Здесь Вы можете написать любую другую информацию о покупке, которой хотите поделиться с участниками и Леруа Мерлен
                                </div>
                            <?php else:?>
                                <div class="label-text">
                                    <label class="control-label">Дополнительная информация</label>
                                </div>
                            <?php endif;?>
                        </div>
                        <div class="buy-list-content">
                            <div class="buy-text-content"><?= $model->additional_information;?></div>
                        </div>
                    </div>
                    <?php endif;?>
                <?php else:?>
                    <?= $model->preparation_purchase;?>
                    <?= $model->store_selection;?>
                    <?= $model->assessment_product;?>
                    <?= $model->conclusion;?>
                    <?= $model->advice;?>
                    <?= $model->additional_information;?>
                <?php endif;?>
            <?php else:?>
                <?= $model->content ?>
            <?php endif;?>
        <?php else:?>
            <?= $model->content ?>
        <?php endif;?>
        <div class="content-buttons">
            <?php if ($model->status == Journal::STATUS_PUBLISHED && Yii::$app->user->can(Rights::GIVE_GIFT, []) && Yii::$app->user->id !== $model->user_id): ?>
                <?= GiveGiftLink::widget([
                    'url' => ['/user/give-gift'],
                    'journalId' => $model->id,
                    'options' => [
                        'class' => 'round-link',
                    ],
                ]) ?>
            <?php endif;?>

            <?php if ($model->status == Journal::STATUS_PUBLISHED): ?>
                <span class="favorite-link-icon">
                   <?= FavoriteLink::widget([
                       'selector' => '.favorite-link',
                       'favoriteIt' => $model->currentUserFavoriteIt(),
                       'url' => ['/user-subscription-favorite/favorite-it', 'id' => $model->id],
                   ]) ?>
                </span>
                <span class="like-link-icon">
                    <?= LikeLink::widget([
                        'likeCount' => $model->getLikeUsersCount(),
                        'likeIt' => $model->currentUserLikeIt(),
                        'url' => ['/journal/like-it', 'id' => $model->id],
                    ]) ?>
                </span>
            <?php endif;?>
        </div>
    </div>
</div>


        <?php if (Yii::$app->request->pathInfo == "journal/check" && $isPurchase): ?>
            <div class="row buy-list-warning">
                <div class="col-md-12 content-buy-check">
                    <div class="buy-list-item">
                        <div class="buy-list-icon">
                            <div class="icon-warning" style=""></div>
                            <div class="buy-list-text-warning">
                                К публикации <span class="colortext-warning">не принимаются</span> посты, посвященные какой-то одной небольшой покупке (до 1000 рублей).
                                <br>К публикации <span class="colortext-warning">не принимаются</span> посты о товарах, которых нет в ассортименте Леруа Мерлен (например, кровати, телевизоры и т.д.) – полный список ассортимента представлен на официальном сайте Леруа Мерлен.
                                Вы можете покупать эти товары или их аналоги у конкурентов, но если мы совсем не продаем такую категорию товаров, пост будет отклонен.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif;?>



<?php if ((count($model->goods) > 0)or(count($model->checkPhotos) > 0)):?>
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
                                            <div class="col-md-3">
                                                Количество
                                            </div>
                                            <div class="col-md-3">
                                                Цена, <i class="fa fa-rub"></i>
                                            </div>
                                            <div class="col-md-3">
                                                Сумма, <i class="fa fa-rub"></i>
                                            </div>
                                            <div class="col-md-3">
                                                Онлайн
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
                                            <div class="col-md-3">
                                                <?= Html::encode($goods->quantity); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <?= Yii::$app->formatter->format(doubleval($goods->price), ['decimal', 2]) ?>
                                            </div>
                                            <div class="col-md-3">
                                                <?= Yii::$app->formatter->format(doubleval($goods->quantity*$goods->price), ['decimal', 2]) ?>
                                            </div>
                                            <div class="col-md-3">
                                                <?php
                                                    if($goods->online) {
                                                ?>
                                                    <i class="fa fa-shopping-cart"></i> <i class="fa fa-credit-card"></i>
                                                <?php
                                                    }
                                                ?>
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
                    <?php if (count($model->checkPhotos) > 0):?>
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


<?php if ((count($model->journalTags) > 0)):?>
    <div class="row">
        <div class="col-md-12 content-container" style="padding-bottom: 0px;">
            <b>Теги:</b>
            <div class="form-group">
                <?php foreach ($model->journalTags as $tag):?>
                    <?= Html::button($tag->name, ['class' => 'btn btn-info btn-xs']) ?>
                <?php endforeach;?>
            </div>
        </div>
    </div>
    <br>
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
