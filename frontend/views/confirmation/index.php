<?php

use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $username string */

$this->title = $username.', Вы успешно подтвердили регистрацию';
?>
<div class="user-activate">
    <h1><?php echo Html::encode($this->title) ?></h1>
    <p>Вы подтвердили регистрацию. Теперь Вы можете начать работу на портале</p>
    <?php echo Html::a('Начать работу', Url::to(['site/login']), ['class' => 'btn btn-primary']) ?>
</div>
