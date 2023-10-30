function JournalEditForm(flag) {
    var _this = this;
    if ($("#journal-preparation_purchase").val() === "")
    {
        textContent();
    }
    spa();

    jQuery(document).ready(function () {
        $("#journal-journaltypes").on("change", function(){
            spa();
             var value = $("#journal-journaltypes").val();
             var postJournal = $("#journal-preparation_purchase").val()
                 + $("#journal-store_selection").val()
                 + $("#journal-assessment_product").val()
                 + $("#journal-conclusion").val()
                 + $("#journal-advice").val()
                 + $("#journal-additional_information").val();
             if (value != null)
             {
                 if (value.length === 1 && value.toString() === "2")
                 {
                     textContent();
                 }
             }
             if (postJournal !== "")
             {
                 if (value == null)
                 {
                     buyTextContent();
                 }
                 else if (value.length > 1 || value.toString() !== "2")
                 {
                     buyTextContent();
                 }
             }
        });

        $("#journal-repairworks").on("change", function(){
            changeTopCssModals();
        });

        $("#journal-repairrooms").on("change", function(){
            if (selectedOtherRoomType()) {
                $(".other-room-container").show();
            } else {
                $(".other-room-container").hide();
            }
            changeTopCssModals();
        });

        $(window).resize(function(){
            changeLeftCssModalGoods();
        });

        $(".info-modal-btn").click(function(e){
            e.preventDefault();
            var target = $(this).data('target');
            $(target).toggle();
            var offset = $(target).data('target-offset');
            $(offset).toggleClass("offset-journal-content");

            changeLeftCssModalGoods();
        });

        $("button.close").click(function(){
            var modal = $(this).data('target');
            $(modal).hide();
            var offset = $(modal).data('target-offset');
            $(offset).removeClass("offset-journal-content");
        });
    });


    function spa() {
        if (selectedType('1')) {
            $(".repair-works-container").show();
            $(".info-work").show();
            changeTopCssModals();
        } else {
            $(".repair-works-container").hide();
            $(".info-work").hide();
            changeTopCssModals();
        }

        if (selectedType('2')) {
            var date = new Date(2020, 8, 1, 0, 0, 0);
            var val = $("#journal-journaltypes").val();
            if (val.length === 1 && val.toString() === "2") {
                if (Date.now() < date && flag === 0){
                    bootbox.dialog({
                        message:
                            "<p class=\"text-center\"><b>Уважаемые участники!</b></p>" +
                            "<p>Мы изменили интерфейс написания постов о Покупках. Многие из Вас мало рассказывают о своем пути выбора товара, а нам важно получать информацию по каждому шагу покупки. Для Вашего удобства новый формат интерфейса разделен по шагам и напомнит вам обо всех ключевых моментах, которые мы просим отразить в посте. После заполнения всех полей, Ваш текст будет показан как единое целое.</p>" +
                            "<p class=\"text-right\">Благодарим Вас за понимание.<br/>Команда Семьи Леруа Мерлен</p>",
                        title: "Внимание!",
                        buttons: {
                            success: {
                                label: "OK",
                                className: "btn-primary",
                            },
                        }
                    });
                }
                $(".buy-edit").show();
                $(".journal-edit-info").hide();
                $(".buy-list-photo").show();
                $(".buy-list-warning").removeClass("hidden");
                $(".field-journal-content").hide();
                $(".buy-list-item .buy-list-photo").show();
            }
            else {
                $(".buy-edit").hide();
                $(".journal-edit-info").show();
                $(".buy-list-photo").hide();
                $(".buy-list-warning").addClass("hidden");
                $(".field-journal-content").show();
                $(".buy-list-item .buy-list-photo").hide();
            }
        } else {
            $(".buy-edit").hide();
            $(".journal-edit-info").show();
            $(".buy-list-photo").hide();
            $(".buy-list-warning").addClass("hidden");
            $(".field-journal-content").show();
        }

        if (selectedOtherRoomType()) {
            $(".other-room-container").show();
        } else {
            $(".other-room-container").hide();
        }
    }

    //проверяет, выбран ли тип помещения "Другое"
    function selectedOtherRoomType() {
        var val = $("#journal-repairrooms").val();
        var id = String(window.otherRoomID);
        return val != null && val.indexOf(id) != -1;
    }

    //проверяет, выбран ли определенный тип журнала
    function selectedType(id) {
        var val = $("#journal-journaltypes").val();
        return val != null && val.indexOf(id) != -1;
    }

    function changeTopCssModals() {
        var offset = $(".field-journal-content").offset();
        var top = (offset.top - 280) + "px";
        $("#info-modal-buy").css("top", top);
        $("#info-modal-work").css("top", top);
    }

    function changeLeftCssModalGoods(){
        var pos = $("#info-modal-goods-btn").offset();
        var left = (pos.left + 120) + "px";
        $("#info-modal-goods").css("left", left);

        if (document.documentElement.clientWidth < 1000) {
            $("#info-modal-goods").css("left", "0px");
        }
    }

    function buyTextContent() {
        CKEDITOR.instances['journal-content'].setData(
            $("#journal-preparation_purchase").val()
            + $("#journal-store_selection").val()
            + $("#journal-assessment_product").val()
            + $("#journal-conclusion").val()
            + $("#journal-advice").val()
            + $("#journal-additional_information").val());
    }
    function textContent() {
        CKEDITOR.instances['journal-preparation_purchase'].setData($("#journal-content").val());
    }
}