<?php
/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
/* @var $user \common\models\User */
$url = \yii\helpers\Url::toRoute(['confirmation/index', 'employeeActivate' => $user->register_summ, 'username' => $user->username], true);
$urlHelp = \yii\helpers\Url::toRoute(['help/view'], true);
?>

<h2>Добро пожаловать на проект Леруа Мерлен Семьи!</h2>
<div>
    <p>Мы очень рады тебе и  надеемся, что проект принесет пользу твоей работе. Обучающие презентации по проекту доступны в разделе <a href="<?php echo $urlHelp;?>">Справка</a></p>
    <p>Для скорейшего присоединения к проекту, необходимо подтвердить регистрацию по ссылке:</p>
    <p><a href="<?php echo $url;?>">Ссылка для подтверждения</a></p>
    <p>Если это был(а) не ты, пожалуйста, проигнорируй это письмо.</p>
    <p>С уважением, команда проекта Семьи Леруа Мерлен.</p>
</div>
