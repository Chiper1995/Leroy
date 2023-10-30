<?php
/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\AppInviteAsset;
use yii\bootstrap\Html;

AppInviteAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title><?= Html::encode($this->title) ?></title>
    <meta name="description" content="<?= Html::encode(Yii::$app->params['description']) ?>" />
    <meta name="keywords" content="<?= Html::encode(Yii::$app->params['keywords']) ?>" />

    <link rel="shortcut icon" href="/favicon.ico">
    <link href="//fonts.googleapis.com/css?family=Lobster|Roboto&subset=cyrillic" rel="stylesheet" type="text/css">
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="container">
    <div class="row">
        <div class="span12">
            <?= $content ?>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>