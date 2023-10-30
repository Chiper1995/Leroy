<?php
namespace common\rbac;

/**
 * Class Rights
 * @package common\config
 */
class Rights
{
    /**
     * Просмотр колонок с ID
     */
    const SHOW_ID_COLUMNS = 'showIdColumns';

    /**
     * Просмотр событий о регистрации новых пользователей
     */
    const SHOW_NEW_USER_REGISTER_NOTIFICATION = 'showNewUserRegisterNotification';

    /**
     * Просмотр событий о регистрации новых пользователей, в том же гоорде
     */
    const SHOW_NEW_USER_REGISTER_IN_MY_CITY_NOTIFICATION = 'showNewUserRegisterInMyCityNotification';

    /**
     * Просмотр событий о удалении юзера
     */
    const SHOW_USER_DELETE_NOTIFICATION = 'showUserDeleteNotification';

    /**
     * Просмотр событий о удалении курируемого юзера
     */
    const SHOW_MY_SUPERVISED_USER_DELETE_NOTIFICATION = 'showMyUserDeleteNotification';

    /**
     * Просмотр событий о отправки записи на проверку
     */
    const SHOW_JOURNAL_ON_CHECK_NOTIFICATION = 'showJournalOnCheckNotification';

    /**
     * Просмотр событий о отправки записи на проверку, в том же гоорде
     */
    const SHOW_JOURNAL_ON_CHECK_IN_MY_CITY_NOTIFICATION = 'showJournalOnCheckInMyCityNotification';

	/**
     * Просмотр уведомлений и счетчика непроверенных постов
     */
    const SHOW_JOURNAL_ON_CHECK_COUNTER = 'showJournalOnCheckCounter';

    /**
     * Просмотр событий о отправки записи по заданию на проверку
     */
    const SHOW_JOURNAL_BY_TASK_ON_CHECK_NOTIFICATION = 'showJournalByTaskOnCheckInMyCityNotification';

    /**
     * Просмотр событий о отправки записи по моему заданию на проверку
     */
    const SHOW_JOURNAL_BY_OWN_TASK_ON_CHECK_NOTIFICATION = 'showJournalByOwnTaskOnCheckInMyCityNotification';

    /**
     * Просмотр событий об отправке записи по заданию на проверку, куратором пользователя
     */
    const SHOW_JOURNAL_BY_TASK_ON_CHECK_BY_USER_CURATOR_NOTIFICATION = 'showJournalByTaskOnCheckByUserCuratorNotification';

    /**
     * Просмотр событий о добавлении фотографий к опубликованному посту
     */
    const SHOW_JOURNAL_PHOTO_ON_CHECK_NOTIFICATION = 'showJournalPhotoOnCheckNotification';

    /**
     * Просмотр событий о публикации записи семьи
     */
    const SHOW_JOURNAL_ON_PUBLISHED_NOTIFICATION = 'showJournalOnPublishedNotification';

    /**
     * Просмотр событий о возврате на редактирование записи семьи
     */
    const SHOW_JOURNAL_ON_RETURN_TO_EDIT_NOTIFICATION = 'showJournalOnReturnToEditNotification';

    /**
     * Просмотр событий о публикации новых фотографий в записи семьи
     */
    const SHOW_JOURNAL_PHOTO_ON_PUBLISHED_NOTIFICATION = 'showJournalPhotoOnPublishedNotification';

    /**
     * Просмотр событий о возврате новых фотографий на редактирование в записи семьи
     */
    const SHOW_JOURNAL_PHOTO_ON_RETURN_TO_EDIT_NOTIFICATION = 'showJournalPhotoOnReturnToEditNotification';

    /**
     * Просмотр событий об изменении куратора семьи
     */
    const SHOW_USER_ON_CHANGE_CURATOR_NOTIFICATION = 'showUserOnChangeCuratorNotification';

    const SHOW_JOURNALS = 'showJournals';
    const SHOW_IN_MY_CITY_JOURNALS = 'showInMyCityJournals';
    const SHOW_FAMILY_JOURNAL = 'showFamilyJournal';

    const SHOW_USERS = 'showUsers';
    const SHOW_FAMILIES = 'showFamilies';
    const SHOW_IN_MY_CITY_FAMILIES = 'showInMyCityFamilies';
    const SHOW_FAMILIES_FOR_FAMILIES = 'showFamiliesForFamilies';
    const SHOW_FAMILIES_PERSONAL_DATA = 'showFamiliesPersonalData';
    const DELETE_FAMILIES = 'deleteFamilies';
    const FAMILY_RESET_PASSWORD = 'familyResetPassword';  // Сбросить пароль для семьи
    const USER_LOGIN_AS = 'userLoginAs';  // Войти как пользователь
    const USER_RESET_NOTIFICATIONS = 'userResetNotifications';  // Сбросить уведомления
    const FAMILY_SET_END_REPAIR_STATUS = 'familySetEndRepairStatus';  // Установить статус "Ремонт окончен"
    const FAMILY_SET_PROF_STATUS = 'familySetProfStatus';  // Установить статус "Профи"
    const SHOW_ADMINISTRATOR_INFO = 'showAdministratorInfo';  // Просмотр полного профиля пользователя не семьи
    const SHOW_ADMINISTRATOR_FULL_INFO = 'showAdministratorFullInfo';  // Просмотр полной информации профиля пользователя не семьи
    const SHOW_FAMILY_POINTS_HISTORY = 'showFamilyPointsHistory';  // Просмотр истории начисления\списания баллов
    const SHOW_DICTS = 'showDicts';

    const SHOW_MY_JOURNAL_RECORDS = 'showMyJournalRecords';

    const SHOW_MY_JOURNAL_FAVORITES = 'showMyJournalFavorites';

    const SHOW_JOURNAL_SMART_SEARCH = 'showJournalSmartSearch';

    /**
     * Редактирование записи дневника
     */
    const EDIT_JOURNAL = 'editJournal';
    const EDIT_MY_JOURNAL = 'editMyJournal';
    const EDIT_MY_JOURNAL_PHOTO = 'editMyJournalPhoto';
    const FILTER_JOURNALS_BY_GOODS = 'filterJournalsByGoods';


    const SHOW_SETTINGS = 'showSettings';

    /**
     * Отображать или нет заработанные баллы, актуально только для ролей типа Семья
     */
    const SHOW_POINTS = 'showPoints';
    const SHOW_POINTS_EARNING = 'showPointsEarning';
    const SHOW_POINTS_SPEND = 'showPointsSpend';
    /* Задания */
    const SHOW_TASKS = 'showTasks';                                                // Просмотр всех заданий
    const SHOW_IN_MY_CITY_TASKS = 'showInMyCityTasks';                             // Просмотр всех заданий, в том же городе
    const SHOW_TASKS_TO_ME = 'showTasksToMe';                                      // Просмотр заданий для меня
    const SHOW_TASK_ON_ADD_TO_ME_NOTIFICATION = 'showTaskOnAddToMeNotification';   // Просмотр оповещений о новом задании для меня

    const EDIT_TASKS = 'editTasks';                                                // Редакирование всех заданий
    const EDIT_OWN_TASKS = 'editOwnTasks';                                         // Редакирование всех заданий, созданных мной

    /* Визиты */
    const SHOW_VISITS = 'showVisits';                                              // Просмотр всех визитов
    const SHOW_IN_MY_CITY_VISITS = 'showInMyCityVisits';                           // Просмотр всех визитов, в том же городе
    const SHOW_VISITS_TO_ME = 'showVisitsToMe';                                    // Просмотр визитов для меня

    const SHOW_VISIT_ON_AGREEMENT_NOTIFICATION = 'showVisitOnAgreementNotification';            // Просмотр оповещений о визите на согласование
    const SHOW_VISIT_AGREED_FAMILY_NOTIFICATION = 'showVisitAgreedFamilyNotification';          // Просмотр оповещений "Визит согласован семьёй"
    const SHOW_VISIT_TIME_EDITED_FAMILY_NOTIFICATION = 'showVisitTimeEditedFamilyNotification'; // Просмотр оповещений "Семья изменила время визита"
    const SHOW_VISIT_CANCELED_FAMILY_NOTIFICATION = 'showVisitCanceledFamilyNotification';      // Просмотр оповещений "Семья отменила визит"
    const SHOW_VISIT_AGREED_NOTIFICATION = 'showVisitAgreedNotification';                       // Просмотр оповещений "Визит согласован администрацией"
    const SHOW_VISIT_CANCELED_NOTIFICATION = 'showVisitCanceledNotification';                   // Просмотр оповещений "Визит отменен администрацией"

    const EDIT_VISITS = 'editVisits';                                              // Редакирование всех визитов
    const EDIT_OWN_VISITS = 'editOwnVisits';                                       // Редакирование всех визитов, созданных мной

    const ADD_COMMENT = 'addComment';
    const EDIT_COMMENT = 'editComment';
    const EDIT_MY_OWN_COMMENT = 'editMyOwnComment';
    const DELETE_COMMENT = 'deleteComment';
    const DELETE_MY_OWN_COMMENT = 'deleteMyOwnComment';
    const DELETE_IN_MY_CITY_COMMENT = 'deleteInMyCityComment';

    const SHOW_JOURNAL_ADD_COMMENT_NOTIFICATION = 'showJournalAddCommentNotification';
    const SHOW_JOURNAL_ADD_IN_MY_CITY_COMMENT_NOTIFICATION = 'showJournalAddInMyCityCommentNotification';
    const SHOW_ME_COMMENTED_JOURNAL_ADD_COMMENT_NOTIFICATION = 'showMeCommentedJournalAddCommentNotification';
    const SHOW_MY_OWN_JOURNAL_ADD_COMMENT_NOTIFICATION = 'showMyOwnJournalAddCommentNotification';

    /*Показывать карты на вкладке "семьи"*/
    const SHOW_FAMILY_LOCATIONS = 'showFamilyLocations';

    /*Показывать гид при первом заходе*/
    const SHOW_GUIDE = 'showGuide';

    /*Редактивароть справку*/
    const EDIT_HELP = 'editHelp';

    /*Списывание баллов*/
    const SPEND_POINTS = 'spendPoints';
    /*Начисление баллов*/
    const EARN_POINTS = 'earnPoints';
    /*Установка куратора*/
    const FAMILY_SET_CURATOR = 'familySetCurator';
    /*Возможность дарить баллы*/
    const GIVE_GIFT = 'giveGift';

    /* Сообщения */
    const CREATE_TICKETS = 'createTickets';
    const CREATE_DIALOGS = 'createDialogs';
    const CREATE_DIALOGS_WITH_SHOP = 'createDialogsWithShop';
    const CREATE_DIALOGS_WITH_SHOP_IN_MY_CITY = 'createDialogsWithShopInMyCity';
    const CREATE_DIALOGS_WITH_FAMILY = 'createDialogsWithFamily';
    const CREATE_DIALOGS_WITH_FAMILY_IN_MY_CITY = 'createDialogsWithFamilyInMyCity';
    const CREATE_DIALOGS_WITH_ADMINISTRATOR = 'createDialogsWithAdministrator';
    /* Читать переписку между пользователем и куратором */
    const READ_DIALOGS_BETWEEN_USER_AND_CURATOR = 'readDialogsBetweenUserAndCurator';

    const SHOW_SEND_MESSAGE_NOTIFICATION = 'sendMessageNotification';

    const SHOW_DEBUG_TOOLBAR = 'showDebugToolbar';

    /* Форум */
    const CREATE_FORUM_THEME = 'createForumTheme';
    const CREATE_FORUM_MAIN_THEME = 'createForumMainTheme';
    const CREATE_FORUM_SUB_THEME = 'createForumSubTheme';
    const CREATE_FORUM_MESSAGES_THEME = 'createForumMessagesTheme';
    const CREATE_FORUM_MESSAGE = 'createForumMessage';

    /* Отчеты */
    const SHOW_REPORTS = 'showReports';

    /* Фильтрация ленты */
    const FILTER_FEED_CURATOR = 'filterFeedCurator';
    const FILTER_FEED_USER = 'filterFeedUser';

    /* Просмотр презентаций в разделе Справка*/
    const SHOW_HELP_PRESENTATION = 'showHelpPresentation';

    /* Анкеты */
    const SHOW_INVITES = 'showInvites';

}
