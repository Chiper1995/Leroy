<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\bootstrap\Html;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

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
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
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
