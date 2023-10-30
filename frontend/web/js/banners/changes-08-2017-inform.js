bootbox.dialog({
	message:
	"<p class=\"text-center\"><b>Уважаемые участники!</b></p>" +
	"<p>Рады сообщить о долгожданном обновлении в нашем проекте: теперь вы можете просматривать дневники других Семей. Нажмите на имя участника, перейдите на его страничку и… теперь ни один пост не останется незамеченным <img src=\"http://families.leroymerlin.ru/assets/63bc7e89/plugins/smiley/images/regular_smile.png\" alt=\"smile\" /></p>" +
	"<p>Следите за историями ремонта/стройки своих фаворитов, черпайте новые знания/идеи, применяйте их на практике, а затем делитесь результатами в своих постах <img src=\"http://families.leroymerlin.ru/assets/63bc7e89/plugins/smiley/images/regular_smile.png\" alt=\"smile\" /></p>" +
	"<p class=\"text-right\">С уважением,<br/>Команда Леруа Мерлен</p>",
	title: "Внимание!",
	buttons: {
		success: {
			label: "OK",
			className: "btn-primary",
			callback: function() {Cookies.set("changes-08-2017-inform", "1", { expires: 36500 });}
		},
	}
});