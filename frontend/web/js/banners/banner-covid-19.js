bootbox.dialog({
	message:
		"<p>Коллеги, в связи с ситуацией в России, мы просим приостановить все исследования на платформе Семьи, которые связаны с взаимодействием с другими людьми (все встречи, задания в магазинах, задания вроде \"Путь клиента\", визиты к клиентам и т.д.) Ведь Ваше здоровье и здоровье наших клиентов - приоритет компании.</p>" +
		"<p>Также напоминаем, что наша платформа позволяет вести удаленную (онлайн) коммуникацию с клиентом, которая как никогда актуальна в такие моменты.</p>" +
		"<p class=\"text-right\">С заботой о Вашем здоровье,<br/>Команда Семьи Леруа Мерлен</p>",
	title: "Внимание!",
	buttons: {
		success: {
			label: "OK",
			className: "btn-primary",
			callback: function() {Cookies.set("banner-covid-19", "1", { expires: 0.5 });}
		},
	}
});