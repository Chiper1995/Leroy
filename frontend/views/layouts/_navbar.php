<?php

/* @var $this \yii\web\View */

use common\models\Journal;
use common\models\notifications\dialog\DialogNewMessageNotification;
use common\rbac\Rights;
use frontend\models\journal\AllJournalSearch;
use frontend\widgets\NavBarUserCard\NavBarUserCard;
use frontend\widgets\NotificationList\NotificationList;
use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

NavBar::begin([
    'brandLabel' => Html::img('/css/img/logo.png?v2'),
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-static-top first-navbar',
        'style' => 'z-index: 1001'
    ],
]);

$firstNavBar = [];

if (Yii::$app->user->isGuest) {
    $firstNavBar[] = ['label' => 'Зарегистрироваться', 'url' => ['/site/signup']];
    $firstNavBar[] = ['label' => 'Войти', 'url' => ['/site/login']];
} else {
    $firstNavBar[] = [
        'label' => Html::icon('user white'),
        'options' => [
            'class' => 'navbar-user-card',
        ],
        'items' => NavBarUserCard::widget()
    ];

    $notificationCount = Yii::$app->user->identity->getNewNotifications()->andWhere(['NOT IN', 'type', DialogNewMessageNotification::$TYPE])->withCommentsAndDialog()->count();
    $firstNavBar[] = [
        'label' =>
            Html::icon('bell white', ['tag'=>'i'])
            . Html::tag('span', $notificationCount, ['class'=>'notification-badge badge'.($notificationCount > 0 ? ' badge-danger' : '')]),
        'options' => [
            'class' => 'notification-bell-link',
        ],
        'linkOptions' => [
            'title' => ($notificationCount > 0 ? '' : 'Нет новых уведомлений'),
            'data' => $notificationCount > 0 ? [] : ['toggle' => 'tooltip', 'placement' => 'bottom',],
            'onclick' => new \yii\web\JsExpression('return false;'),
        ],
		'dropDownOptions' => [
			'class' => 'notification-list-container'
		],
        'items' =>
            $notificationCount > 0 ? [NotificationList::widget()] : null
    ];

    $newMessagesCount = Yii::$app->user->identity->getNewNotifications()->andWhere(['type' => DialogNewMessageNotification::$TYPE])->count();
    $firstNavBar[] = [
        'label' =>
            Html::tag('i', '', ['class'=>'white fa fa-envelope'])
            . Html::tag('span', $newMessagesCount, ['class'=>'notification-badge badge'.($newMessagesCount > 0 ? ' badge-danger' : '')]),
        'visible' => \Yii::$app->user->can(Rights::SHOW_SEND_MESSAGE_NOTIFICATION),
        'linkOptions' => [
            'title' => 'Сообщения',
            'data' => ['toggle' => 'tooltip', 'placement' => 'bottom',],
        ],
        'url' => ['/dialog/index']
    ];

    if (\Yii::$app->user->can(Rights::SHOW_JOURNAL_ON_CHECK_COUNTER)) {
		$onCheckJournalsCount = (new AllJournalSearch())->search(Journal::className(), ['AllJournalSearch' => ['status' => Journal::STATUS_ON_CHECK]])->totalCount;

		$firstNavBar[] = [
			'label' =>
				Html::tag('i', '', ['class' => 'white fa fa-check-square-o'])
				. Html::tag('span', $onCheckJournalsCount, ['class' => 'notification-badge badge' . ($onCheckJournalsCount > 0 ? ' badge-danger' : '')]),
			'linkOptions' => [
				'title' => 'На проверку',
				'data' => ['toggle' => 'tooltip', 'placement' => 'bottom',],
			],
			'url' => ['/journal/all-journals', 'status' => Journal::STATUS_ON_CHECK]
		];

		if (($onCheckJournalsCount > 0) && (Yii::$app->cache->get('SHOW_JOURNAL_ON_CHECK_COUNTER_ALERT_' . \Yii::$app->user->id) === false)) {
			$message = \Yii::t('app', 'У вас на проверке {n, plural, one{# пост} few{# поста} many{# постов} other{# постов}}', ['n' => $onCheckJournalsCount]);
			$js =
				'bootbox.alert({' .
				'   title: "Проверка постов",' .
				'	message: "' . $message . '" ' .
				'});';
			$this->registerJs($js);
			Yii::$app->cache->set('SHOW_JOURNAL_ON_CHECK_COUNTER_ALERT_' . \Yii::$app->user->id, 1, 10800);
		}
	}
    
    if (isset($_SESSION['logged_in_user_id'])){
       //$message1 = \Yii::t('app','Начиная с 1 августа будет изменен порядок получения вознаграждения за участие. Теперь при накоплении 800 баллов, вы сможете обменять их на купон на 10% скидку в магазине Леруа Мерлен.' );
       $message1 = '<p>Благодарим вас за участие в нашем проекте! Спасибо за ваши истории ремонта, стройки и благоустройства дома. Мы всегда с интересом их читаем, анализируем и используем информацию для улучшения работы наших магазинов. </p>'.
                    '<p><b>С 1 августа 2021г. мы вводим новые правила обмена баллов.</b> Накопленные баллы можно будет обменять только на купон с 10% скидкой. При накоплении 800 баллов вы получаете скидочный купон,'. 
                    'который действует во всех торговых центрах «Леруа Мерлен» на территории РФ в течение 12 месяцев с момента выдачи. Купон можно будет получить у вашего куратора.</p>'. 
                    '<p>Также обращаем ваше внимание, что в разделе «Справка» появился подраздел «Правила участия в проекте Семьи Леруа Мерлен», где мы отобразили всю важную информацию по нашему проекту.</p>'.
                    '<p>Готовы ответить на ваши вопросы!</p>'.
                    '<p>С уважением,<br/>'.
                    'Команда Леруа Мерлен.</p>'; 
       $js =
                'bootbox.alert({' .
                '   title: "Уважаемые участники, добрый день!",' .
                '       message: "' . $message1 . '" ' .
                '});';
        $this->registerJs($js);
        unset($_SESSION['logged_in_user_id']);
    }

    $firstNavBar[] = [
        'label' => Html::icon('question-sign white'),
        'options' => ['class' => 'help-link',],
        'linkOptions' => [
            'title' => 'Справка',
            'data' => ['toggle' => 'tooltip', 'placement' => 'bottom',],
        ],
        'url' => ['/help/view']
    ];
}

echo Nav::widget([
    'encodeLabels'=>false,
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $firstNavBar,
]);
NavBar::end();

$this->registerJsFile('@web/js/notification-list.min.js', ['position' => \yii\web\View::POS_END, 'depends'=>[\yii\web\JqueryAsset::className()]]);
$this->registerJs('$(".notification-list-container").notificationList()');
?>

<?php
NavBar::begin([
    'brandLabel' => false,
    'brandUrl' => false,
    'options' => [
        'class' => 'navbar-inverse navbar-static-top',
    ],
]);

$secondNavBar = [];

if (!Yii::$app->user->isGuest) {

    // ANDR Скрыл форум для семей на время тестирования
//    if (!Yii::$app->user->can(\common\models\User::ROLE_FAMILY))
//        $secondNavBar[] = ['label' => Html::tag('i', '', ['class'=>'white fa fa-comments-o', 'title'=>'Клуб ремонта', 'data'=>['toggle'=>'tooltip', 'placement'=>'bottom']]).'<span class="hidden-sm">&nbsp;'.'Клуб ремонта</span>', 'url' => ['forum/index'],];

    $secondNavBar[] = ['label' => Html::icon('th white', ['tag'=>'i', 'title'=>'Лента записей семей', 'data'=>['toggle'=>'tooltip', 'placement'=>'bottom']]).'<span class="hidden-sm">&nbsp;'.'Лента</span>', 'url' => ['journal/index'],];
    $secondNavBar[] = ['label' => Html::icon('star white', ['tag'=>'i', 'title'=>'Мое избранное', 'data'=>['toggle'=>'tooltip', 'placement'=>'bottom']]).'<span class="hidden-sm">&nbsp;'.'Мое избранное</span>', 'url' => ['journal/my-subscription'], 'visible' => \Yii::$app->user->can(Rights::SHOW_MY_JOURNAL_FAVORITES),];
    $secondNavBar[] = ['label' => Html::icon('book white', ['tag'=>'i', 'title'=>'Мои записи', 'data'=>['toggle'=>'tooltip', 'placement'=>'bottom']]).'<span class="hidden-sm">&nbsp;'.'Мои записи</span>', 'url' => ['journal/my-journal'], 'visible' => \Yii::$app->user->can(Rights::SHOW_MY_JOURNAL_RECORDS),];
    $secondNavBar[] = ['label' => Html::icon('check white', ['tag'=>'i', 'title'=>'Мои задания', 'data'=>['toggle'=>'tooltip', 'placement'=>'bottom']]).'<span class="hidden-sm">&nbsp;'.'Мои задания</span>', 'url' => ['task/my-tasks'], 'visible' => \Yii::$app->user->can(Rights::SHOW_TASKS_TO_ME),];
    //$secondNavBar[] = ['label' => Html::icon('time white', ['tag'=>'i', 'title'=>'Визиты ко мне', 'data'=>['toggle'=>'tooltip', 'placement'=>'bottom']]).'<span class="hidden-sm">&nbsp;'.'Визиты ко мне</span>', 'url' => ['visit/my-visits'], 'visible' => \Yii::$app->user->can(Rights::SHOW_VISITS_TO_ME),];
    $secondNavBar[] = ['label' => Html::icon('book white', ['tag'=>'i', 'title'=>'Записи всех семей', 'data'=>['toggle'=>'tooltip', 'placement'=>'bottom']]).'<span class="hidden-sm">&nbsp;'.'Дневники</span>', 'url' => ['journal/all-journals'], 'visible' => \Yii::$app->user->can(Rights::SHOW_JOURNALS),];
    //$secondNavBar[] = ['label' => Html::icon('time white', ['tag'=>'i', 'title'=>'Визиты', 'data'=>['toggle'=>'tooltip', 'placement'=>'bottom']]).'<span class="hidden-sm">&nbsp;'.'Визиты</span>', 'url' => ['visit/index'], 'visible' => \Yii::$app->user->can(Rights::SHOW_VISITS),];
    $secondNavBar[] = ['label' => Html::icon('check white', ['tag'=>'i', 'title'=>'Задания', 'data'=>['toggle'=>'tooltip', 'placement'=>'bottom']]).'<span class="hidden-sm">&nbsp;'.'Задания</span>', 'url' => ['task/index'], 'visible' => \Yii::$app->user->can(Rights::SHOW_TASKS),];
    $secondNavBar[] = [
        'label' => Html::tag('i', '', ['class'=>'white fa fa-users', 'title'=>'Семьи', 'data'=>['toggle'=>'tooltip', 'placement'=>'bottom']]).'<span class="hidden-sm">&nbsp;'.'Семьи</span>',
        'url' => ['user/families'],
        'visible' => \Yii::$app->user->can(Rights::SHOW_FAMILIES)];
    $secondNavBar[] = [
        'label' => Html::tag('i', '', ['class'=>'white fa fa-users', 'title'=>'Семьи', 'data'=>['toggle'=>'tooltip', 'placement'=>'bottom']]).'<span class="hidden-sm">&nbsp;'.'Семьи</span>',
        'url' => ['user/families-search'],
        'visible' => \Yii::$app->user->can(Rights::SHOW_FAMILIES_FOR_FAMILIES),];
    $secondNavBar[] = ['label' => Html::icon('user white', ['tag'=>'i', 'title'=>'Пользователи', 'data'=>['toggle'=>'tooltip', 'placement'=>'bottom']]).'<span class="hidden-sm hidden-md">&nbsp;'.'Пользователи</span>', 'url' => ['user/index'], 'visible' => \Yii::$app->user->can(Rights::SHOW_USERS),];
    $secondNavBar[] = [
        'label' => Html::icon('th-list white', ['tag'=>'i', 'title'=>'Списки', 'data'=>['toggle'=>'tooltip', 'placement'=>'bottom']]).'<span class="hidden-sm hidden-md">&nbsp;'.'Списки</span>',
        'visible' => \Yii::$app->user->can(Rights::SHOW_DICTS),
        'items' => [
            ['label' => 'Города', 'url' => ['/city/index'],],
            ['label' => 'Объекты ремонта', 'url' => ['/object-repair/index'],],
            ['label' => 'Помещения для ремонта', 'url' => ['/room-repair/index'],],
            ['label' => 'Работы', 'url' => ['/work-repair/index'],],
            ['label' => 'Магазины покупки товаров', 'url' => ['/goods-shop/index'],],
        ],
    ];
    $secondNavBar[] = [
        'label' => Html::tag('i', '', ['class'=>'white fa fa-plus-circle', 'title'=>'Другое', 'data'=>['toggle'=>'tooltip', 'placement'=>'bottom']]) . '<span class="hidden-sm hidden-md">&nbsp;Другое</span>',
        'visible' => \Yii::$app->user->can(Rights::SHOW_SETTINGS) || \Yii::$app->user->can(Rights::EDIT_HELP) || \Yii::$app->user->can(Rights::SHOW_REPORTS) || \Yii::$app->user->can(Rights::SHOW_INVITES),
        'items' => [
            ['label' => Html::tag('i', '', ['class'=>'white fa fa-cogs']) . '&nbsp;&nbsp;Настройки', 'url' => ['settings/index'], 'visible' => \Yii::$app->user->can(Rights::SHOW_SETTINGS),],
            ['label' => Html::tag('i', '', ['class'=>'white fa fa-question-circle']).'&nbsp;&nbsp;Справка', 'url' => ['help/index'], 'visible' => \Yii::$app->user->can(Rights::EDIT_HELP),],
            ['label' => Html::tag('i', '', ['class'=>'white fa fa-line-chart']).'&nbsp;&nbsp;Отчеты', 'url' => ['report/index'], 'visible' => \Yii::$app->user->can(Rights::SHOW_REPORTS),],
            ['label' => Html::tag('i', '', ['class'=>'white fa fa-users']).'&nbsp;&nbsp;Анкеты', 'url' => ['invite/list'], 'visible' => \Yii::$app->user->can(Rights::SHOW_INVITES),],
        ]
    ];
}

echo Nav::widget([
    'encodeLabels'=>false,
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $secondNavBar,
]);
NavBar::end();
?>
