## Разворачивание

-composer install
-npm install

## Конфигурация LAMP

- версия php - 5.6
- apache на порт 8080 (Чтобы не переключать каждый раз на дебаг режим)
- режим mysql - только NO_AUTO_CREATE_USER
- php.ini - post_max_size и upload_max_filesize должны быть больше 5M

## Конфиги для морды

Создать файл project/common/config/environments/params-dev.php (или params-prod.php).

Прописать следующий код в этом файле по примеру:
```
<?php

return [
    'db.connectionString' => 'mysql:host=localhost;dbname=lmfam',
    'db.username' => 'root',
    'db.password' => 'qwerty',
    'db.tablePrefix' => 'lmfam',
    'sphinx.connectionString' => 'mysql:host=0;port=9306;',
    'sphinx.username' => '',
    'sphinx.password' => '',
    'cache' => [
        'class' => \yii\caching\FileCache::class,
    ],
    //настройки отправки почты
    'mailer.class' => 'yii\swiftmailer\Mailer',
    'mailer.useFileTransport' => false,
    'mailer.transport.class' => 'Swift_SmtpTransport',
    'mailer.transport.host' => 'smtp.mail.ru',
    'mailer.transport.username' => '',
    'mailer.transport.password' => '',
    'mailer.transport.port' => '465',
    'mailer.transport.encryption' => 'ssl',
    'mailer.messageConfig.from' => ['no-reply-leroy-merlin@mail.ru'=>'Семьи Леруа Мерлен'],
    //апи ключ к карте
    'apikey' => '123456',
];
```

## Конфиги для консоли

В файле project/console/config/environments/params-prod.php
добавить недостающие строки

```
<?php

return [
    'env.code' => 'prod',

    // cache settings -if APC is not loaded, then use DbCache
    'cache' => extension_loaded('apc') ?
        [
            'class' => \yii\caching\ApcCache::className(),
        ] :
        [
            'class' => \yii\caching\DbCache::className(),
            'db' => 'db',
            'cacheTable' => '{{%cache}}',
        ],
    'db.connectionString' => 'mysql:host=localhost;dbname=lmfam',
    'db.username' => 'root',
    'db.password' => 'qwerty',
    'db.tablePrefix' => 'lmfam',
];
```

Теперь можно выполнить миграции:
php yiic migrate


## Включить дебаг режим

В project/frontend/web/index.php изменить константу на true.
```
define('IS_DEV_SERVER', $_SERVER['SERVER_PORT'] == 8080);
```



## Разворачивание sphinx

- Внимание, sudo apt-get install sphinxsearch устанавливает старую версию, нужна 3.0.3
- версия 3.0.3 распространяется только в виде бинарников
- качаем бинарники с сайта
- распаковываем
- перемещаем в нужную директорию

- конфиги взять с сервера  /etc/sphinxsearch/sphinx.conf
- прописать там свои доступы к бд, свои пути к рунтаймовым файлам сфинкса
- поместить конфиги в папку path/to/sphinxsearch/bin

- устанавливаем зависимости indexer'a
- apt-get install libmysqlclient-dev libpq-dev unixodbc-dev
- apt-get install libmariadb-client-lgpl-dev-compat

- качаем словари с http://sphinxsearch.com/downloads/dicts/
- ложим их в sphinx/dicts/ (папка может быть другой)

- индексируем из папки sphinxsearch/bin
- ./indexer  --all --rotate
- возможно придется указать путь к конфигу прямо ./indexer -c /sphinx/conf/sphinx.conf --all --rotate

- запускаем демона ./searchd
- возможно придется указать путь к конфигу прямо ./searchd -c /sphinx/conf/sphinx.conf

- проверяем работу
- проверяем, запущен ли процесс ps aux|grep searchd
- заходим в сфинкс mysql -h0 -P9306
- CALL SUGGEST('аврора', 'journals', 20 as limit, 50 as reject, 2 as delta_len, 1 as max_edits)

- останавливаем демона: ./searchd --stop и sudo killall searchd










## Cобытия, уведомления, почта

Архитектура приложения устроена так, что все события (кроме некоторых пакетных) вызываются только глобально без listeners.
Реализовано через конфиги. В основном события расположены либо в шаблонах, либо во контроллерах (не в моделях). Предлагаю придерживаться подобной структуры.

Создаем новое событие:
- /common/events/AppEvents.php - прописываем константу
- /common/events/AppEventsHandler.php - обработчик события
- /frontend/config/events.php - присобачиваем AppEventsHandler к событию
- /console/config/events.php - если событие должно работать в консоле

обработчики прикрепляются не в events.php, а опосредованно в AppEventsHandler.php .

Вызыв события:
- \Yii::$app->trigger(AppEvents::EVENT_USER_ACTIVATE, new Event(['data' => $data]));

Такие штуки, как отправка уведомлений, почты и т.д. делаются через специальный компонент, вызываемый в хенлдере события. 
Все скрипты отправки сообщений находятся в /common/components/UserEmailSender. Отправка уведомлений - в /common/models/notifications
Таким образом все находится в одном месте, а не раскидано по всему проекту.



## Как создать новое правило и назначить его ролям

- прописываем константы в /common/rbac/Rights.php
- создаем дополнительные условия для проверки прав в /common/rbac/rules (необязательно)
- описываем это правило в /common/config/auth_rights.php, 
здесь нужно прописать название правила, тип, описание, путь к /common/rbac/rules и children, если есть
- в /common/config/auth.php указываем, каким ролям можно использовать это правило.

Проверка на права в коде:
```
if (Yii::$app->user->can(Rights::EDIT_COMMENT, ['comment'=>$comment]) {}
```

Если какие-то права наследуются, например EDIT_MY_OWN_COMMENT в children наследование EDIT_COMMENT. 
То Yii::$app->user->can(Rights::EDIT_COMMENT) разрешает доступы и по правилам EDIT_MY_OWN_COMMENT.
