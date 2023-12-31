bootbox.dialog({
	message:
		"<p class=\"text-center\"><b>Уважаемые участники!</b></p>" +
		"<p>В связи со сложной эпидемиологической ситуацией наши магазины не могут производить обмен баллов на товары. Мы очень надеемся на ваше понимание и поддержку в этот непростой период, когда большинство наших магазинов закрыто, а те, что работают, испытывают колоссальную нагрузку. Наш проект продолжается, и вы можете писать посты, копить баллы, находить идеи и общаться друг с другом как и раньше.</p>" +
		"<p class=\"text-right\">Желаем всем крепкого здоровья!<br/>Команда Семьи Леруа Мерлен</p>",
	title: "Внимание!",
	buttons: {
		success: {
			label: "OK",
			className: "btn-primary",
			callback: function() {Cookies.set("banner-covid-19-family", "1", { expires: 0.5 });}
		},
	}
});