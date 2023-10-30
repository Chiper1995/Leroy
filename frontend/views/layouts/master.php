<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\bootstrap\Html;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use frontend\widgets\PopupFavorite\PopupFavorite;

AppAsset::register($this);
\frontend\assets\FontAwesomeAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= $this->render('_browser_statusbar_color', ['color'=>'#4db748'])?>
    <?= $this->render('_favicon')?>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- Google Tag Manager -->
    <script type="text/javascript">
      (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
      new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
      j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,'script','dataLayer','GTM-TWVZ2P9');
    </script>
    <!-- End Google Tag Manager -->
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function() {(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
        ym(56124529, "init", { clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true });
    </script>
    <noscript>
        <div>
            <img src="https://mc.yandex.ru/watch/56124529" style="position:absolute; left:9999px;" alt="" />
        </div>
    </noscript>
    <!-- /Yandex.Metrika counter -->

</head>
<body>
<?php $this->beginBody() ?>
<!-- Google Tag Manager (noscript) -->
<noscript>
  <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TWVZ2P9" height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
<!-- Попап с просьбой добавить в избранное -->
<?= PopupFavorite::widget() ?>

<div class="wrap">
    <div class="breadcrumbs-bar navbar-static-top">
        <div class="container">
            <div class="breadcrumbs-bar-inner">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],

                ]) ?>
            </div>
        </div>
    </div>

    <?= $this->render('_navbar'); ?>

    <div class="container">
        <div class="row">
            <?= Alert::widget() ?>
        </div>
    </div>

    <?= $content ?>
</div>

<?php
$js = <<<SCRIPT
$('body').tooltip({selector: '[data-toggle="tooltip"]'});
$('body').popover({selector: '[data-toggle="popover"]'});

$('.notification-bell-link .dropdown-menu, .navbar-user-card .dropdown-menu').click(function(e) {
    e.stopPropagation();
});
SCRIPT;
$this->registerJs($js);
?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
