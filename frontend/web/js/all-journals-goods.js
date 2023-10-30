function AllJournalsGoods(elementId, treeViewElementId, modalElementId, formElementId) {
    var $this = this;

    this.elementId = '#' + elementId;
    this.treeViewElementId = '#' + treeViewElementId;
    this.modalElementId = '#' + modalElementId;
    this.formElementId = '#' + formElementId;

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
        var p = $($this.elementId).find('ul');
        p.append('<li data-id="'+id+'"><span class="goods-delete">&times;</span>'+name+'<input type="hidden" value="'+id+'" name="AllJournalSearch[goods_filter][]"></li>');
        $this.visiblePrompt();
    };

    this.removeRow = function(id) {
        var p = $($this.elementId).find('ul');
        p.find('li[data-id='+id+']').remove();
        $this.visiblePrompt();
    };

    this.visiblePrompt = function () {
        var p = $($this.elementId);
        if (p.find('ul > li').length > 0) $($this.elementId+' .prompt').hide(); else $($this.elementId+' .prompt').show();
    };

    this.submitTimeout = undefined;

    jQuery(document).ready(function () {

        var $body = $('body');

        // Удаление товара
        $body.on("click", $this.elementId+" .goods-delete", function () {
            var $$this = $(this);
            var id = $$this.parents('li:first').attr('data-id');
            var node = $this.getNodeById(id);

            if (node!=undefined) {
                $($this.treeViewElementId).treeview('unselectNode', [node, {silent: true}]);
                $this.removeRow(id);

                if ($this.submitTimeout != undefined) {
                    clearTimeout($this.submitTimeout);
                    $this.submitTimeout = undefined;
                }
                $this.submitTimeout = setTimeout(function(){$($this.formElementId).submit();}, 1500);
            }

            return false;
        });

        // Закрытие окна выбора товаров
        $($this.modalElementId).on('hidden.bs.modal', function (e) {
            $($this.formElementId).submit();
        });

    });
}
