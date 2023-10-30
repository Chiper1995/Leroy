jQuery(document).ready(function () {
    //правильные варианты
    var domains = ["gmail.com", "mail.ru", "yandex.ru", "ya.ru", "rambler.ru",
        "bk.ru", "inbox.ru", "list.ru", "icloud.com", "hotmail.com", "outlook.com",
    ];
    //костыль для неправильных вариантов, котороые потом заменятся регуляркой
    //к сожалению mailcheck не исправляет домен сам
    domains.push("gmail.ru", "mail.com", "yandex.com", "ya.com", "rambler.com",
        "bk.com", "inbox.com", "list.com", "icloud.ru", "hotmail.ru", "outlook.ru"
    );
    var secondLevelDomains = ["gmail", "mail", "yandex", "ya", "rambler",
        "bk", "inbox", "list", "icloud", "hotmail", "outlook",
    ];
    var topLevelDomains = ["com", "ru"];


    $('#profile-email').on('blur', function() {
        $(this).mailcheck({
            domains: domains,
            secondLevelDomains: secondLevelDomains,
            topLevelDomains: topLevelDomains,
            suggested: function(element, suggestion) {
                var oldVal = $(element).val();
                messageErr();

                //проверка на вхождение в массив domains
                if (domains.indexOf(suggestion.domain) != -1) {
                    messageErr(oldVal + ' заменено на ' + suggestion.full);
                    $(element).val(suggestion.full);
                }

                myMailcheck(element, oldVal);
            },
            empty: function(element) {
                var oldVal = $(element).val();
                messageErr();
                myMailcheck(element, oldVal);
            }
        });
    });


    function myMailcheck(element, oldVal) {
        var val = $(element).val();

        //проверка на пустоту
        if (!String(val).trim()) {
            messageErr('Необходимо заполнить «Email».');
            return;
        }

        //проверка на правильность структуры
        if (!validateEmail(val)) {
            messageErr('Значение «Email» не является правильным email адресом.');
            return;
        }

        //проверка на исключительные случаи
        if (!isRightMail(val)) {
            messageErr(oldVal + ' заменено на ' + replaceMail(val));
            $(element).val(replaceMail(val));
        }
    }


    function messageErr(message) {
        if (message !== undefined) {
            $(".field-profile-email").removeClass("has-success");
            $(".field-profile-email").addClass("has-error");
            $(".field-profile-email").find("p.help-block").html(message);
        } else {
            $(".field-profile-email").removeClass("has-error");
            $(".field-profile-email").addClass("has-success");
            $(".field-profile-email").find("p.help-block").html('');
        }
    }

    function validateEmail(email) {
        var pattern  = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

        return pattern .test(email);
    }


    //чистые функции
    function isRightMail(email) {
        var mailArr = String(email).split('@');
        var rightMail = getRightMail(mailArr[1]);

        return mailArr[1] == rightMail;
    }

    function replaceMail(email) {
        var mailArr = String(email).split('@');
        var rightMail = getRightMail(mailArr[1]);

        return mailArr[0] + "@" + rightMail;
    }

    function getRightMail(email) {
        if (email == "gmail.ru") email = "gmail.com";
        if (email == "icloud.ru") email = "icloud.com";
        if (email == "mail.com") email = "mail.ru";
        if (email == "yandex.com") email = "yandex.ru";
        if (email == "ya.com") email = "ya.ru";
        if (email == "rambler.com") email = "rambler.ru";
        if (email == "bk.com") email = "bk.ru";
        if (email == "list.com") email = "list.ru";
        if (email == "inbox.com") email = "inbox.ru";

        return email;
    }

});