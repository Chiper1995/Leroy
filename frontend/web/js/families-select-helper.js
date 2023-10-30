function FamiliesSelectHelper(pjaxId, gridId, formId, selectedPjaxId)
{
    var _this = this;

    this.gridId = gridId;
    this.pjaxId = pjaxId;
    this.formId = formId;
    this.selectedPjaxId = selectedPjaxId;

    this.grid = $(this.gridId);
    this.pjax = $(this.pjaxId);
    this.selectedPjax = $(this.selectedPjaxId);

    this.inputHiddenName = 'Task[families][][user_id]';
    this.inputClassPrefix = 'family-';
    this.saveButtonSelector = '.save-btn';

    this.init =  function() {
        var _body = $("body");
        _this.cutLengthStrings();
        // Заворачиваем отправку фильтра в pjax
        _body.on('beforeFilter', _this.gridId , function(event) {
            var frm = $(_this.gridId).find('form')[0];
            event = {currentTarget: frm, preventDefault: function(){}};
            $.pjax.submit(event, _this.pjaxId, {"push":false,"replace":false,"timeout":false,"scrollTo":false});
            frm.remove();
            return false;
        });

        _this.pjax.on('pjax:beforeSend', function(xhr, options) {
            _this.setSelectedToForm();
        });

        _this.pjax.on('pjax:end', function(xhr, options) {
            _this.cutLengthStrings();
            _this.grid = $(_this.gridId);
            _this.setSelectedFromForm();
        });

        _body.on('click', _this.saveButtonSelector, function() {
            _this.setSelectedToForm();
            _this.refreshSelectedFamilies();
            return true;
        });

        _this.setSelectedFromForm();
    };

    this.cutLengthStrings = function() {
        var size = 50;
        var newsContent = $('.table-family-content').each(function(index, value) {
            var newsText = $(value).html();
            if(newsText.length > size){
                $(value).html(newsText.slice(0, size) + ' ...');
            }
        });
    };


    this.setSelectedToForm = function() {
        var data = _this.grid.yiiGridView('data');
        if (data.selectionColumn) {
            _this.grid.find("input[name='" + data.selectionColumn + "']").each(function () {
                var id = $(this).parent().closest('tr').data('key');
                if ($(this).is(':checked')) {
                    if (_this.selectedPjax.find('.'+_this.inputClassPrefix+id).length == 0)
                        $('<input type="hidden" class="'+_this.inputClassPrefix+id+'" name="'+_this.inputHiddenName+'" value="'+id+'" />').appendTo(_this.selectedPjax);
                }
                else
                    _this.selectedPjax.find('.'+_this.inputClassPrefix+id).remove();
            });
        }
    };

    this.setSelectedFromForm = function() {
        var data = _this.grid.yiiGridView('data');
        if (data.selectionColumn) {
            _this.grid.find("input[name='" + data.selectionColumn + "']").each(function () {
                var id = $(this).parent().closest('tr').data('key');
                if (_this.selectedPjax.find('.'+_this.inputClassPrefix+id).length != 0)
                    $(this).prop('checked', true);
            });
        }
    };

    this.refreshSelectedFamilies = function() {
        var frm = $(_this.formId);
        var event = {currentTarget: frm[0], preventDefault: function(){}};
        $.pjax.submit(event, _this.selectedPjax, {"push":false,"replace":false,"timeout":false,"scrollTo":false});
    };

    this.init();
}
