<?php
use yii\web\View;

$apikey = \Yii::$app->params['apikey'];
?>

<div id="YMaps" style="height: 600px"></div>

<!-- Ассинхронная загрузка API карты в head -->
<?php $this->registerJsFile('https://api-maps.yandex.ru/2.1?apikey='. $apikey. '&load=package.full&lang=ru_RU', ['position' => View::POS_HEAD])?>
<!-- Скрипт, обрабатывающий карту -->
<?php $this->registerJsFile('@web/js/family-locations.min.js', ['position' => View::POS_END, 'depends'=>\yii\web\JqueryAsset::className()])?>
