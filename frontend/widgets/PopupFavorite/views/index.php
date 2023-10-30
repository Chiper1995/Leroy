<?php
//попап окно с предложением добавить сайт в закладки
?>

<div id="modalAddFavorite" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content left-modal bright-text">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <?= $message ?>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!-- Скрипт, вызывающий модальное окно после загрузки страницы -->
<?php $this->registerJs(
    <<<JS
    $(document).ready(function() {

        // Функция для определения "мобильности" браузера
        function MobileDetect() {
           var UA = navigator.userAgent.toLowerCase();
           return (/android|webos|iris|bolt|mobile|iphone|ipad|ipod|iemobile|blackberry|windows phone|opera mobi|opera mini/i
              .test(UA)) ? true : false ;
        }
        // Если браузер НЕ мобильный, отображаем окно
        if (!MobileDetect()) {
            $("#modalAddFavorite").modal('show');
        }
    });
JS
);?>
