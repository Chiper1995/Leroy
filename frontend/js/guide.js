jQuery(document).ready(function () {
    window.scrollTo(0, 0);

    //initialize instance
    var enjoyhint_instance = new EnjoyHint({
        onEnd:function(){
            $("#popup").addClass('show');
            $.post(window.location, {guide_viewed: 1});
        }
    });

    // steps
    var enjoyhint_script_steps = [
        {
            'next .navbar-brand img' :
                '<div><p>Рады приветствовать Вас в нашем сообществе!</p></div>',
            'shape': 'circle',
            'showSkip': false,
        },
        {
            'next .navbar-user-card' :
                '<div><p>Первым делом заполните профиль.<br/>'+
                'Ваши фото, рассказ о себе и о ремонте<br/>'+
                'помогут нам лучше Вас узнать!</p></div>',
            'showSkip': false,
        },
        {
            'next .notification-bell-link' :
                '<div><p>В этом разделе будут появляться <br class="visible-sm visible-xs" />уведомления о новых событиях:<br/>'+
                'например, Вам отправили задание <br class="visible-sm visible-xs" />или хотят прийти к Вам в гости.<br/><br class="visible-sm visible-xs" />'+
                'Здесь же будут появляться сообщения <br class="visible-sm visible-xs" />о новых функциях в дневниках.<br/><br/></p></div>',
            'shape': 'circle',
            'radius': 38,
            'showSkip': false,
        },
        {
            'next .help-link' :
                '<div><p>А в этом разделе Вы сможете найти ответы на вопросы о том,<br/>'+
                'как правильно писать публикации в дневнике,<br/>'+
                'как зарабатывать и тратить баллы и<br/>'+
                'и много другой полезной информации.<br/><br/>'+
                'Успехов в ремонте!<br/>'+
                'С нетерпением ждем Ваших историй!</p></div>',
            'shape': 'circle',
            'radius': 30,
            'nextButton' : {text: "Поехали!"},
            'showSkip': false,
        }
    ];

    enjoyhint_instance.set(enjoyhint_script_steps);
    enjoyhint_instance.run();
});