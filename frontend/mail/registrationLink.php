<?php
/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
/* @var $invite \common\models\Invite */
$url = \yii\helpers\Url::toRoute(['registration/index', 'id' => $invite->session_id], true);
$urlHelp = \yii\helpers\Url::toRoute(['help/view'], true);
?>

<h2>Уважаем<?php echo (int)$invite->sex === 1 ? 'ый' : 'ая'; ?> <?php echo trim($invite->fio); ?>!</h2>
<div>
	<p>Мы рады, что Вы присоединились к проекту-исследованию «<a href="https://families.leroymerlin.ru/">Семьи Леруа Мерлен</a>». Теперь Вы сможете делиться своим опытом ремонта или строительства, получать советы от Леруа Мерлен и других участников проекта, менять Леруа Мерлен с помощью отзывов о нашей работе и ... конечно же зарабатывать баллы, которые можно обменять на товары в наших магазинах.</p>
	<p>Пожалуйста, зарегистрируйтесь по <a href="<?php echo $url; ?>">ссылке</a> и заполните свой профиль. Возникнут вопросы – используйте раздел «<a href="<?php echo $urlHelp;?>">Справка</a>» на платформе.</p>
	<p>Обращаем внимание, что количество участников ограничено, поэтому рекомендуем вступить в проект в течение суток после получения этого письма.</p>
	<p>Ждем Ваших постов!</p>
	<p>С уважением, команда проекта Семьи Леруа Мерлен.</p>
</div>
