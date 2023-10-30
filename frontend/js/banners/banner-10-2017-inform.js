bootbox.dialog({
	message:
		"<div class=\"text-center\"><img src=\"/images/banner-1.png\" height=\"1026\" /> </div>",
	title: "ЯРМАРКА ШАНСОВ",
	buttons: {
		success: {
			label: "OK",
			className: "btn-primary",
			callback: function() {Cookies.set("banner-10-2017-inform", "1", { expires: 36500 });}
		},
	},
	size: "large"
}).off("shown.bs.modal");