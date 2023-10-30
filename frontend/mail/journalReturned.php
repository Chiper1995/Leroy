<?php
/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

/* @var $help string */
/* @var $user \common\models\User */
use yii\helpers\Html;
use yii\helpers\Url;

$url = Url::toRoute(['journal/view', 'id' => $journal->id], true);
?>

<div>
    Добрый день!<br/>
    Спасибо Вам, что делитесь с нами историями о текущем ремонте : )
    Пожалуйста, внесите уточнения по Вашему посту: <?= Html::a($url, $url) ?> .
</div>
<br/>
<div>
    Ждем Вас!<br/>
    Команда Леруа Мерлен
</div>
