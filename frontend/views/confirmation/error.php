<?php

use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Что-то пошло не так';
?>
<div class="user-activate">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Вы не подтвердили регистрацию, ссылка не верна</p>
    <?php echo Html::a('Перейти на главную', Url::to(['site/login']), ['class' => 'btn btn-primary']) ?>
</div>
