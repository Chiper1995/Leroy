bootbox.dialog({
	message:
	"<p class=\"text-center\"><b>Дорогие участники!</b></p>" +
	"<p>Спасибо, что весь этот год Вы были с нами – ломали и снова строили, пилили и строгали, клеили и красили, фонтанировали неожиданными решениями, удивляя команду Леруа Мерлен и своих соседей по ремонту!</p>" +
	"<p>Уже совсем скоро наступит Новый год! Хотим пожелать Вам крепкого здоровья, семейного уюта и достатка, новых интересных решений для преображения Вашего жилья. Мечтайте, планируйте и рискуйте – Леруа Мерлен во всем Вас поддержит!</p>" +
	"<p class=\"text-right\">С уважением,<br/>Команда Леруа Мерлен</p>",
	title: "Внимание!",
	buttons: {
		success: {
			label: "OK",
			className: "btn-primary",
			callback: function() {Cookies.set("banner-2017-12-30-inform", "1", { expires: 36500 });}
		},
	}
});