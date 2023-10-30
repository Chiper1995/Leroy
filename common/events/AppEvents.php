<?php
namespace common\events;


class AppEvents
{
    /**
     * Регистрация нового пользователя
     */
    const EVENT_NEW_USER_REGISTER = 'newUserRegister';

    /**
     * Активация пользователя
     */
    const EVENT_USER_ACTIVATE = 'userActivate';

    /**
     * Удаление пользователя
     */
    const EVENT_USER_DELETE = 'userDelete';

    /**
     * Смена куратора у пользователя
     */
    const EVENT_USER_CHANGE_CURATOR = 'userChangeCurator';

    /**
     * Отправка записи на проверку
     */
    const EVENT_JOURNAL_ON_CHECK = 'journalOnCheck';

    /**
     * Отправка записи на проверку
     */
    const EVENT_JOURNAL_BY_TASK_ON_CHECK = 'journalByTaskOnCheck';

    /**
     * Добавление фотографий в опубликованный пост
     */
    const EVENT_JOURNAL_PHOTO_ON_CHECK = 'journalPhotoOnCheck';

    /**
     * Запись опубликована
     */
    const EVENT_JOURNAL_ON_PUBLISHED = 'journalOnPublished';

    /**
     * Запись опубликована
     */
    const EVENT_JOURNAL_ON_TYPE_CHANGED = 'journalOnTypeChanged';

    /**
     * Запись возвращена на редактирование
     */
    const EVENT_JOURNAL_ON_RETURN_TO_EDIT = 'journalOnReturnToEdit';

    /**
     * Новые фотографии в записи опубликованы
     */
    const EVENT_JOURNAL_PHOTO_ON_PUBLISHED = 'journalPhotoOnPublished';

    /**
     * Новые фотографии в записи возвращены на редактирование
     */
    const EVENT_JOURNAL_PHOTO_ON_RETURN_TO_EDIT = 'journalPhotoOnReturnToEdit';

    /**
     * Создано новое задание пользователю
     */
    const EVENT_TASK_ADDED = 'taskAdded';

    /**
     * Визит отправлен на согласование пользователю
     */
    const EVENT_VISIT_ON_AGREEMENT = 'visitOnAgreement';

    /**
     * Визит согласован семьёй
     */
    const EVENT_VISIT_AGREED_FAMILY = 'visitAgreedFamily';

    /**
     * Семья изменила время визита
     */
    const EVENT_VISIT_TIME_EDITED_FAMILY = 'visitTimeEditedFamily';

    /**
     * Семья отменила визит
     */
    const EVENT_VISIT_CANCELED_FAMILY = 'visitCanceledFamily';

    /**
     * Визит согласован администрацией
     */
    const EVENT_VISIT_AGREED = 'visitAgreed';

    /**
     * Визит отменен администрацией
     */
    const EVENT_VISIT_CANCELED = 'visitCanceled';

    /**
     * Восстановление пароля
     */
    const EVENT_USER_PASSWORD_RESET = 'userPasswordReset';

    /**
     * Новый комментарий
     */
    const EVENT_JOURNAL_ADD_COMMENT = 'journalAddComment';

    /**
     * Удалили комментарий
     */
    const EVENT_JOURNAL_DELETE_COMMENT = 'journalDeleteComment';

    /**
     * Новое сообщение
     */
    const EVENT_SEND_MESSAGE = 'sendMessage';

    /**
     * Отправка письма с инфой о списке отсутствующих юзеров
     */
    const EVENT_SEND_MAIL_LAST_VISITS = 'sendMailLastVisits';

    /**
     * Отправка письма для подтверждения регистрации сотрудника
     */
    const EVENT_EMPLOYEE_REGISTRATION_CONFIRMATION = 'employeeRegistrationConfirmation';

	/**
	 * Ссылка на регистрацию
	 */
	const EVENT_REGISTRATION_LINK_SEND = 'registrationLinkSend';

    /**
     * Списание баллов у пользователя
     */
    const EVENT_USER_SPEND_POINTS = 'userSpendPoints';

    /**
     * Начисление баллов у пользователя
     */
    const EVENT_USER_EARNING_POINTS = 'userEarningPoints';
}
