bootbox.dialog({
	message:
	"<p class=\"text-center\"><b>Дорогие участники проекта «Семьи Леруа Мерлен»!</b></p>" +
	"<p>Спешим вам сообщить о новых возможностях нашей платформы:</p>" +
	"<ul>" +
	"	<li>Теперь все самые желанные посты любимого соседа будут отображаться в меню «Мои подписки». Его постоянным читателем можно стать, нажав слово «подписка» рядом с именем автора в публикации</li>" +
	"	<li>Выражайте свои симпатию и поддержку, лайкая его пост в правом нижнем углу записи…</li>" +
	"	<li>А если творчество участника так сильно нравится, что и целого «лайка» мало, то дарите свои баллы – в левом нижнем углу поста есть значок «подарок»</li>" +
	"</li>" +
	"<p class=\"text-right\">С уважением,<br/>Команда Леруа Мерлен</p>",
	title: "Внимание!",
	buttons: {
		success: {
			label: "OK",
			className: "btn-primary",
			callback: function() {Cookies.set("banner-2018-07-26_changes-info", "1", { expires: 36500 });}
		},
	},
	size: "large"
});