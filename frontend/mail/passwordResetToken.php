<?php
/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

/* @var $user \common\models\User */

?>

<h1>Восстановление пароля</h1>
<?php $u = \yii\helpers\Url::toRoute(['site/reset-password', 'token'=>$user->password_reset_token], true);?>
<div>
    Здравствуйте!<br/>
    Вы запросили восстановление пароля на портале «Семьи Леруа Мерлен»,<br/>
    cсылка для восстановления учетной записи <b><?= $user->username?></b>:<br/>
    <?= \yii\helpers\Html::a($u, $u)?>
</div>
<br/>
<div>
    <b>
        Будем рады видеть Вас снова!<br/>
        Команда Леруа Мерлен Россия
    </b>
</div>