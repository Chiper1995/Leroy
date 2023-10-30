<?php

use common\rbac\Rights;
use yii\rbac\Item;

return [
    // Доступы
    Rights::SHOW_ID_COLUMNS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр колонок с ID',
    ],

    Rights::SHOW_DEBUG_TOOLBAR => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр Debug Toolbar',
    ],

    // Регистрация пользователя
    Rights::SHOW_NEW_USER_REGISTER_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр событий о регистрации новых пользователей',
    ],

    Rights::SHOW_NEW_USER_REGISTER_IN_MY_CITY_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр событий о регистрации новых пользователей, в том же городе',
        'ruleName' => \common\rbac\rules\UserInMyCityRule::className(),
        'children' => [
            Rights::SHOW_NEW_USER_REGISTER_NOTIFICATION,
        ]
    ],

    // Удаление пользователя
    Rights::SHOW_USER_DELETE_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр событий об удалении пользователей',
    ],

    Rights::SHOW_MY_SUPERVISED_USER_DELETE_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр событий об удалении курируемых пользователей',
        'ruleName' => \common\rbac\rules\DeleteMyUserRule::className(),
        'children' => [
            Rights::SHOW_USER_DELETE_NOTIFICATION,
        ]
    ],

    // Смена куратора
    Rights::SHOW_USER_ON_CHANGE_CURATOR_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр события о смене куратора у семьи',
        'ruleName' => \common\rbac\rules\UserOnChangeCuratorNotificationRule::className(),
    ],

    // Новая запись в дневнике
    Rights::SHOW_JOURNAL_PHOTO_ON_CHECK_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр событий о добавлении фотографий в уже опубликованный пост',
    ],

    Rights::SHOW_JOURNAL_ON_CHECK_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр событий об отправке на публикацию новой записи',
        'children' => [
            Rights::SHOW_JOURNAL_PHOTO_ON_CHECK_NOTIFICATION,
        ]
    ],

    Rights::SHOW_JOURNAL_ON_CHECK_IN_MY_CITY_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр событий об отправке на публикацию новой записи, в том же городе',
        'ruleName' => \common\rbac\rules\JournalOnCheckInMyCityNotificationRule::className(),
        'children' => [
            Rights::SHOW_JOURNAL_ON_CHECK_NOTIFICATION,
        ]
    ],

	Rights::SHOW_JOURNAL_ON_CHECK_COUNTER => [
		'type' => Item::TYPE_PERMISSION,
		'description' => 'Просмотр уведомлений и счетчика непроверенных постов',
	],

    Rights::SHOW_JOURNAL_BY_TASK_ON_CHECK_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр событий о отправки записи по заданию на проверку',
        'children' => [
            Rights::SHOW_JOURNAL_PHOTO_ON_CHECK_NOTIFICATION,
        ]
    ],

    Rights::SHOW_JOURNAL_BY_OWN_TASK_ON_CHECK_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр событий о отправки записи по моему заданию на проверку',
        'ruleName' => \common\rbac\rules\JournalByOwnTaskOnCheckInMyCityNotificationRule::className(),
        'children' => [
            Rights::SHOW_JOURNAL_BY_TASK_ON_CHECK_NOTIFICATION,
        ]
    ],

    Rights::SHOW_JOURNAL_BY_TASK_ON_CHECK_BY_USER_CURATOR_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр событий об отправке записи по заданию на проверку, куратором пользователя',
        'ruleName' => \common\rbac\rules\JournalByTaskOnCheckByUserCuratorNotificationRule::className(),
        'children' => [
            Rights::SHOW_JOURNAL_BY_TASK_ON_CHECK_NOTIFICATION,
        ]
    ],

    // Публикация записи
    Rights::SHOW_JOURNAL_ON_PUBLISHED_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр событий о публикации записи семьи',
        'ruleName' => \common\rbac\rules\MyJournalRule::className(),
        'children' => [
            Rights::SHOW_JOURNAL_PHOTO_ON_PUBLISHED_NOTIFICATION,
        ]
    ],

    // Просмотр событий о возврате на редактирование записи семьи
    Rights::SHOW_JOURNAL_ON_RETURN_TO_EDIT_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр событий о возврате на редактирование записи семьи',
        'ruleName' => \common\rbac\rules\MyJournalRule::className(),
        'children' => [
            Rights::SHOW_JOURNAL_PHOTO_ON_RETURN_TO_EDIT_NOTIFICATION,
        ]
    ],

    // Просмотр событий о публикации новых фотографий в записи семьи
    Rights::SHOW_JOURNAL_PHOTO_ON_PUBLISHED_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр событий о публикации новых фотографий в записи семьи',
    ],

    // Просмотр событий о возврате на редактирование новых фотографий в записи семьи
    Rights::SHOW_JOURNAL_PHOTO_ON_RETURN_TO_EDIT_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр событий о возврате на редактирование новых фотографий в записи семьи',
    ],

    //
    Rights::SHOW_JOURNALS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр всех дневников',
        'children' => [
            Rights::SHOW_FAMILY_JOURNAL,
        ]
    ],

    Rights::SHOW_IN_MY_CITY_JOURNALS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр всех дневников, в том же городе',
        'ruleName' => \common\rbac\rules\UserInMyCityRule::className(),
        'children' => [
            Rights::SHOW_JOURNALS,
        ]
    ],

    Rights::SHOW_FAMILY_JOURNAL => [
        'type' => Item::TYPE_PERMISSION,
        'ruleName' => \common\rbac\rules\UserIsFamilyAndNotMeRule::className(),
        'description' => 'Просмотр семьёй дневника другой семьи',
    ],

    Rights::SHOW_MY_JOURNAL_RECORDS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр моих записей',
    ],

    Rights::SHOW_MY_JOURNAL_FAVORITES => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Мое избранное',
    ],

	Rights::SHOW_JOURNAL_SMART_SEARCH => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Отображать смарт-поиск',
    ],

    Rights::EDIT_JOURNAL => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Редактирование всех записей дневников',
    ],

    Rights::EDIT_MY_JOURNAL => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Редактирование неопубликованной записи дневника',
        'ruleName' => \common\rbac\rules\EditJournalRule::className(),
        'children' => [
            Rights::EDIT_JOURNAL,
        ]
    ],

    Rights::EDIT_MY_JOURNAL_PHOTO => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Добавление фото в опубликованную записи дневника',
        'ruleName' => \common\rbac\rules\EditMyJournalPhotoRule::className(),
    ],

    Rights::FILTER_JOURNALS_BY_GOODS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Фильтрация записей дневников по товарам',
    ],

    /*************  КОММЕТАРИИ  *****************/
	Rights::ADD_COMMENT => [
		'type' => Item::TYPE_PERMISSION,
		'description' => 'Создание комментария',
	],

    Rights::EDIT_COMMENT => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Редактирование комментария',
    ],

    Rights::EDIT_MY_OWN_COMMENT => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Редактирование моего комментария',
        'ruleName' => \common\rbac\rules\EditJournalMyOwnCommentRule::className(),
        'children' => [
            Rights::EDIT_COMMENT,
        ]
    ],

    Rights::DELETE_COMMENT => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Удаление комментария',
        'ruleName' => \common\rbac\rules\DeleteJournalCommentRule::className(),
    ],

    Rights::DELETE_MY_OWN_COMMENT => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Удаление моего комментария',
        'ruleName' => \common\rbac\rules\DeleteJournalMyOwnCommentRule::className(),
        'children' => [
            Rights::DELETE_COMMENT,
        ]
    ],

    Rights::DELETE_IN_MY_CITY_COMMENT => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Удаление комментария семьи в моём городе',
        'ruleName' => \common\rbac\rules\DeleteJournalInMyCityCommentRule::className(),
        'children' => [
            Rights::DELETE_COMMENT,
        ]
    ],

    /*** NOTIFICATIONS   СОЗДАНИЕ КОММЕНТАРИЯ ***/

    Rights::SHOW_JOURNAL_ADD_COMMENT_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Уведомление о новом комментарии',
        'ruleName' => \common\rbac\rules\NotMyCommentNotificationRule::className(),
    ],

    Rights::SHOW_JOURNAL_ADD_IN_MY_CITY_COMMENT_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Уведомление о новом комментарии',
        'ruleName' => \common\rbac\rules\AddInMyCityCommentNotificationRule::className(),
        'children' => [
            Rights::SHOW_JOURNAL_ADD_COMMENT_NOTIFICATION,
        ]
    ],

    Rights::SHOW_ME_COMMENTED_JOURNAL_ADD_COMMENT_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Уведомление о новом комментарии, в записи, которую я комментировал',
        'ruleName' => \common\rbac\rules\MeCommentedJournalNotificationRule::className(),
        'children' => [
            Rights::SHOW_JOURNAL_ADD_COMMENT_NOTIFICATION,
        ]
    ],

    Rights::SHOW_MY_OWN_JOURNAL_ADD_COMMENT_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Уведомление о новом комментарии, в записи, которую я комментировал',
        'ruleName' => \common\rbac\rules\CommentInMyJournalNotificationRule::className(),
        'children' => [
            Rights::SHOW_JOURNAL_ADD_COMMENT_NOTIFICATION,
        ]
    ],

    /********  КАРТЫ И АДРЕСА РЕМОНТА  *********/

    Rights::SHOW_FAMILY_LOCATIONS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр карт на вкладке семьи',
    ],

    /*************  ВИЗИТЫ  ********************/

    Rights::SHOW_VISITS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр всех визитов',
    ],

    Rights::SHOW_IN_MY_CITY_VISITS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр всех визитов, в том же городе',
        'ruleName' => \common\rbac\rules\VisitInMyCityRule::className(),
        'children' => [
            Rights::SHOW_VISITS,
        ]
    ],

    Rights::SHOW_VISITS_TO_ME => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр визитов ко мне',
        'ruleName' => \common\rbac\rules\VisitToMeRule::className(),
    ],

    Rights::EDIT_VISITS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Редакирование всех визитов',
        'ruleName' => \common\rbac\rules\EditVisitRule::className(),
    ],

    Rights::EDIT_OWN_VISITS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Редакирование всех визитов, созданных мной',
        'ruleName' => \common\rbac\rules\EditOwnVisitRule::className(),
        'children' => [
            Rights::EDIT_VISITS,
        ]
    ],

    // NOTIFICATIONS

    Rights::SHOW_VISIT_ON_AGREEMENT_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр оповещений о новом визите на согласование',
        'ruleName' => \common\rbac\rules\NotificationVisitForFamilyRule::className(),
    ],

    Rights::SHOW_VISIT_AGREED_FAMILY_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр оповещений "Визит согласован семьёй"',
        'ruleName' => \common\rbac\rules\NotificationVisitForCreatorRule::className(),
    ],

    Rights::SHOW_VISIT_TIME_EDITED_FAMILY_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр оповещений "Семья изменила время визита"',
        'ruleName' => \common\rbac\rules\NotificationVisitForCreatorRule::className(),
    ],

    Rights::SHOW_VISIT_CANCELED_FAMILY_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр оповещений "Семья отменила визит"',
        'ruleName' => \common\rbac\rules\NotificationVisitForCreatorRule::className(),
    ],

    Rights::SHOW_VISIT_AGREED_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Визит согласован администрацией',
        'ruleName' => \common\rbac\rules\NotificationVisitForFamilyRule::className(),
    ],

    Rights::SHOW_VISIT_CANCELED_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Визит отменен администрацией',
        'ruleName' => \common\rbac\rules\NotificationVisitForFamilyRule::className(),
    ],

    /*************  ЗАДАНИЯ  ********************/

    Rights::SHOW_TASKS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр всех заданий',
    ],

    Rights::SHOW_IN_MY_CITY_TASKS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр всех заданий, в том же городе',
        'ruleName' => \common\rbac\rules\TaskInMyCityRule::className(),
        'children' => [
            Rights::SHOW_TASKS,
        ]
    ],

    Rights::SHOW_TASK_ON_ADD_TO_ME_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр оповещений о новом задании',
        'ruleName' => \common\rbac\rules\NotificationOnAddedTaskRule::className(),
    ],

    Rights::SHOW_TASKS_TO_ME => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр заданий для меня',
        'ruleName' => \common\rbac\rules\TaskToMeRule::className(),
    ],

    Rights::EDIT_TASKS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Редакирование всех заданий',
    ],

    Rights::EDIT_OWN_TASKS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Редакирование всех заданий, созданных мной',
        'ruleName' => \common\rbac\rules\OwnTaskRule::className(),
        'children' => [
            Rights::EDIT_TASKS,
        ]
    ],

    /*************  ЗАДАНИЯ END ********************/

    Rights::SHOW_USERS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр всех пользователей',
        'children' => [
            Rights::SHOW_FAMILIES,
            Rights::SHOW_FAMILIES_PERSONAL_DATA,
        ]
    ],

    Rights::SHOW_FAMILIES => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр пользователей-семей',
    ],

    Rights::SHOW_IN_MY_CITY_FAMILIES => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр пользователей-семей',
        'ruleName' => \common\rbac\rules\UserInMyCityRule::className(),
        'children' => [
            Rights::SHOW_FAMILIES,
        ]
    ],

	Rights::SHOW_FAMILIES_PERSONAL_DATA => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр персональных данных пользователей-семей',
    ],

	Rights::SHOW_FAMILIES_FOR_FAMILIES => [
		'type' => Item::TYPE_PERMISSION,
		'description' => 'Просмотр пользователей-семей семьями',
	],

    Rights::DELETE_FAMILIES => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Удаление семей',
    ],

    Rights::FAMILY_RESET_PASSWORD => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Сбросить пароль для семьи',
    ],

    Rights::USER_LOGIN_AS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Войти как пользователь',
    ],

    Rights::USER_RESET_NOTIFICATIONS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Сбросить уведомления',
    ],

    Rights::FAMILY_SET_END_REPAIR_STATUS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Установить статус "Ремонт окончен"',
    ],

    Rights::FAMILY_SET_PROF_STATUS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Установить статус "Профи"',
    ],

    Rights::SHOW_ADMINISTRATOR_INFO => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр профиля пользователя не семьи',
    ],

    Rights::SHOW_ADMINISTRATOR_FULL_INFO => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр полной информации профиля пользователя не семьи',
        'children' => [
            Rights::SHOW_ADMINISTRATOR_INFO,
        ]
    ],

    Rights::SHOW_DICTS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр всех справочников',
    ],

    Rights::SHOW_SETTINGS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Редактирование настроек',
    ],

    Rights::SHOW_POINTS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Отображать или нет заработанные баллы, актуально только для ролей типа Семья',
    ],

    Rights::SHOW_POINTS_EARNING => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр начисленных баллов',
    ],
    Rights::SHOW_POINTS_SPEND => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр списанных баллов',
    ],

    Rights::SHOW_GUIDE => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Отображать гайд при первом входе',
    ],

    Rights::EDIT_HELP => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Редактирование справки',
    ],

    Rights::SPEND_POINTS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Списывание баллов',
    ],

    Rights::EARN_POINTS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Начисление баллов',
    ],

	Rights::SHOW_FAMILY_POINTS_HISTORY => [
		'type' => Item::TYPE_PERMISSION,
		'description' => 'Просмотр истории начисления/списания баллов',
	],

    Rights::FAMILY_SET_CURATOR => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Установка куратора',
    ],

    Rights::GIVE_GIFT => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Подарить баллы',
    ],

    /*************  СООБЩЕНИЯ ********************/

    Rights::SHOW_SEND_MESSAGE_NOTIFICATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Уведомление о новом сообщении',
        'ruleName' => \common\rbac\rules\dialog\DialogMessageToMeRule::className(),
    ],

    Rights::CREATE_TICKETS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Создание обращений к куратору',
    ],

    Rights::CREATE_DIALOGS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Создание диалогов',
    ],

    Rights::CREATE_DIALOGS_WITH_SHOP => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Создание диалогов с магазинами',
        'children' => [
            Rights::CREATE_DIALOGS,
        ]
    ],

    Rights::CREATE_DIALOGS_WITH_SHOP_IN_MY_CITY => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Создание диалогов с магазинами в моём городе',
        'children' => [
            Rights::CREATE_DIALOGS_WITH_SHOP,
        ]
    ],

    Rights::CREATE_DIALOGS_WITH_FAMILY => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Создание диалогов с семьями',
        'children' => [
            Rights::CREATE_DIALOGS,
        ]
    ],

    Rights::CREATE_DIALOGS_WITH_FAMILY_IN_MY_CITY => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Создание диалогов с семьями в моём городе',
        'children' => [
            Rights::CREATE_DIALOGS_WITH_FAMILY,
        ]
    ],

    Rights::CREATE_DIALOGS_WITH_ADMINISTRATOR => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Создание диалогов с администраторами',
        'children' => [
            Rights::CREATE_DIALOGS,
        ]
    ],

    Rights::READ_DIALOGS_BETWEEN_USER_AND_CURATOR => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Чтение переписки между пользователем и куратором',
    ],

    /***********************************  ФОРУМ   **************************************************/

    Rights::CREATE_FORUM_THEME => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Создание тем',
    ],

        Rights::CREATE_FORUM_MAIN_THEME => [
            'type' => Item::TYPE_PERMISSION,
            'description' => 'Создание основных разделов',
            'children' => [
                Rights::CREATE_FORUM_THEME,
            ],
            'ruleName' => \common\rbac\rules\forum\CreateForumMainTheme::className(),
        ],

        Rights::CREATE_FORUM_SUB_THEME => [
            'type' => Item::TYPE_PERMISSION,
            'description' => 'Создание подтем',
            'children' => [
                Rights::CREATE_FORUM_THEME,
            ],
            'ruleName' => \common\rbac\rules\forum\CreateForumSubTheme::className(),
        ],

        Rights::CREATE_FORUM_MESSAGES_THEME => [
            'type' => Item::TYPE_PERMISSION,
            'description' => 'Создание обсуждений',
            'children' => [
                Rights::CREATE_FORUM_THEME,

            ],
            'ruleName' => \common\rbac\rules\forum\CreateForumMessagesTheme::className(),
        ],

    Rights::CREATE_FORUM_MESSAGE => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Создание соообщения',
    ],

    /***********************************  REPORTS   **************************************************/

    Rights::SHOW_REPORTS => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Отчеты',
    ],

    /***********************************  ЛЕНТА   **************************************************/

    Rights::FILTER_FEED_CURATOR => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Фильтрация ленты для кураторов',
    ],

    Rights::FILTER_FEED_USER => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Фильтрация ленты для юзеров',
    ],
    // Справка
    Rights::SHOW_HELP_PRESENTATION => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр презентации в разделе Справка',
    ],

	// Анкеты
	Rights::SHOW_INVITES => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Просмотр анкет',
    ],
];
