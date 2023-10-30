function Goods(elementId, treeViewElementId) {
    var $this = this;

    this.elementId = '#' + elementId;
    this.treeViewElementId = '#' + treeViewElementId;

    this.onNodeSelected = function (undefined, item) {
        $this.addRow(item.id, item.name);
    };

    this.onNodeUnselected = function (undefined, item) {
        $this.removeRow(item.id);
    };

    this.getNodeById = function(id) {
        var nodes = $($this.treeViewElementId).treeview('getNodes');
        for (var key in nodes) {
            if (nodes[key].id == id) {
                return nodes[key];
            }
        }
        return undefined;
    };
    
    this.addRow = function(id, name) {
        var rowIndex;
        var p = $($this.elementId);
        p.append('<div class="row goods goods-' + (rowIndex = p.find('div.goods').length) + '" data-id="'+id+'"></div>');
        var d = p.find('.goods-' + rowIndex);
        p.find('.goods-template').children().clone().appendTo(d);

        var sid = 'goods-shop-id-'+rowIndex;

        d.find('.goods-name span').text(name);
        d.find('.goods-id').attr('name', 'Journal[goods]['+rowIndex+'][goods_id]').val(id);
        d.find('.goods-quantity').attr('name', 'Journal[goods]['+rowIndex+'][quantity]').val(1);
        d.find('.goods-price').attr('name', 'Journal[goods]['+rowIndex+'][price]').val(0.00);
        d.find('.goods-online').attr('name', 'Journal[goods]['+rowIndex+'][online]').val(1);
        d.find('.goods-shop-id').attr('id', sid).attr('name', 'Journal[goods]['+rowIndex+'][goods_shop_id]').val(null);

        $('#'+sid).on('select2:opening', initS2Open).on('select2:unselecting', initS2Unselect);
        jQuery
            .when(jQuery('#'+sid).select2({"allowClear":false,"theme":"bootstrap","width":"100%","placeholder":"","language":"ru"}))
            .done(initS2Loading(sid, '.select2-container--bootstrap', '', true));

        $this.visibleHeader();
    };

    this.removeRow = function(id) {
        var p = $($this.elementId);
        p.find('[data-id='+id+']').html('').hide();

        $this.visibleHeader();
    };

    this.visibleHeader = function () {
        var p = $($this.elementId);
        if (p.find('.goods').length > 1) $('.goods-header').parents('.goods').show(); else $('.goods-header').parents('.goods').hide();
    };

    // (123456789.12345).formatMoney(2, '.', ',');
    // or (123456789.12345).formatMoney(2);
    Number.prototype.formatMoney = function(c, d, t){
        var n = this,
            c = isNaN(c = Math.abs(c)) ? 2 : c,
            d = d == undefined ? "," : d,
            t = t == undefined ? " " : t,
            s = n < 0 ? "-" : "",
            i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    };

    jQuery(document).ready(function () {

        var $body = $('body');

        // Удаление товара
        $body.on("click", $this.elementId+" .goods-delete", function () {
            var $$this = $(this);

            bootbox.dialog({
                message: "Удалить этот товар?",
                title: "Подтверждение",
                buttons: {
                    success: {
                        label: "Да",
                        className: "btn-primary btn-with-margin-right",
                        callback: function() {
                            var p = $$this.parents('div.goods');
                            var id = p.find('.goods-id').val();
                            var node = $this.getNodeById(id);

                            if (node!=undefined) {
                                $($this.treeViewElementId).treeview('unselectNode', [node, {silent: true}]);
                                $this.removeRow(id);
                            }
                        }
                    },
                    cancel: {
                        label: "Отмена",
                        className: "btn-default",
                        callback: function() {}
                    },
                }
            });
            return false;
        });

        // Подсчет суммы
        $body.on("change keyup", $this.elementId+" .goods-quantity, "+$this.elementId+" .goods-price", function () {
            var g = $(this).parents('.goods');
            var p = g.find(".goods-price").val();
            var q = g.find(".goods-quantity").val();

            p = p.replace(' ', '').replace(',', '.');
            q = q.replace(' ', '').replace(',', '.');

            p = $.isNumeric(p) ? p : 0;
            q = $.isNumeric(q) ? q : 0;

            var s = p*q;
            g.find('.goods-sum span').text(s.formatMoney());
        });
    });

}