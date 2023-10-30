<?php


use common\models\Journal;
use dosamigos\ckeditor\CKEditor;
use yii\bootstrap\Html;

$this->registerJs("CKEDITOR.plugins.addExternal('autogrow', '/autogrow/plugin.js', '');");
/**
 * @var $journal Journal
 */
?>

<div>
    <div class="buy-list-item-information buy-list-item">
        <div class="buy-list-icon">
            <div class="icon-information" style=""></div>
            <div class="buy-list-text">
                Пост с покупками помогает нам понять, как Вы выбираете материалы, где их покупаете и сколько тратите на тот или иной материал в Вашем текущем ремонте.
                <br>Пишите про товары, которые были куплены на момент участия в нашем проекте или не более 3 месяцев назад.
                <br>Пожалуйста, заполните текстовые блоки внизу, после заполнения текст будет показан как единое целое:
            </div>
        </div>
    </div>
    <br>

    <div class="buy-list-item">
        <div class="buy-list-icon">
            <div class="icon-preparation-for-purchase" style=""></div>
            <div class="text-content <?= ($model->hasErrors('preparation_purchase')) ? 'has-error' : '' ?>">
                <label class="control-label">Подготовка к покупке</label>
                <br><span class="colortext">Если речь идет о функциональном товаре:</span> Как Вы поняли какой именно товар Вам нужен? Какие источники информации использовали для понимания?
                <br><span class="colortext">Если речь идет о декоративном товаре:</span> Какими источниками вдохновлялись при выборе товара? С какими предметами интерьера товар должен <br>сочетаться/контрастировать по цвету или стилю? Расскажите про декоративную задумку.
            </div>
        </div>
        <div class="ckeditor-journal-buy">
            <?= $form->field($model, 'preparation_purchase')->widget(CKEditor::className(), [
                'preset' => 'custom',
                'clientOptions' => [
                    'toolbarGroups' => [
                        ['name' => 'links', 'groups' => ['basicstyles', 'colors', 'insert', 'list']],
                    ],
                    'removeButtons' => 'Strike,Subscript,Superscript,Image,Flash,Table,HorizontalRule,SpecialChar,PageBreak,Iframe,Unlink,Anchor',
                    'extraPlugins' => 'autogrow',
                    'removePlugins' => 'resize, elementspath',
                    'autoGrow_onStartup' => true,
                    'autoGrow_minHeight' => '200',
                    'skin' => 'bootstrapck'
                ],

            ]) ?>
        </div>

    </div>

    <div class="buy-list-item">
        <div class="buy-list-icon">
            <div class="icon-store-selection" style=""></div>
            <div class="text-content <?= ($model->hasErrors('store_selection')) ? 'has-error' : '' ?>">
                <label class="control-label">Выбор магазина</label>
                <br>Вы смотрели этот товар в других магазинах или на сайтах других магазинов? Если да, расскажите: в каких ещё магазинах смотрели, что понравилось в этих магазинах, а что нет.
                Почему? Что послужило ключевым фактором при принятии решения о выборе магазина для покупки?
            </div>
        </div>
        <div class="ckeditor-journal-buy">
            <?= $form->field($model, 'store_selection')->widget(CKEditor::className(), [

                'preset' => 'custom',
                'clientOptions' => [
                    'toolbarGroups' => [
                        ['name' => 'links', 'groups' => ['basicstyles', 'colors', 'insert', 'list']],
                    ],
                    'removeButtons' => 'Strike,Subscript,Superscript,Image,Flash,Table,HorizontalRule,SpecialChar,PageBreak,Iframe,Unlink,Anchor',
                    'extraPlugins' => 'autogrow',
                    'removePlugins' => 'resize, elementspath',
                    'autoGrow_onStartup' => true,
                    'autoGrow_minHeight' => '200',
                    'skin' => 'bootstrapck'
                ],

            ]) ?>
        </div>
    </div>

    <div class="buy-list-item">
        <div class="buy-list-icon">
            <div class="icon-assessment-product" style=""></div>
            <div class="text-content <?= ($model->hasErrors('assessment_product')) ? 'has-error' : '' ?>">
                <label class="control-label">Оценка выкладки товара</label>
                <br>Насколько удобно Вам было найти и выбрать нужный товар в магазине/на сайте? Обращались ли Вы к продавцу-консультанту за помощью в поиске и выборе товара?
                <br>Как можно улучшить выкладку товара/страницу сайта, чтобы было удобнее выбирать?
            </div>
        </div>
        <div class="ckeditor-journal-buy">
            <?= $form->field($model, 'assessment_product')->widget(CKEditor::className(), [

                'preset' => 'custom',
                'clientOptions' => [
                    'toolbarGroups' => [
                        ['name' => 'links', 'groups' => ['basicstyles', 'colors', 'insert', 'list']],
                    ],
                    'removeButtons' => 'Strike,Subscript,Superscript,Image,Flash,Table,HorizontalRule,SpecialChar,PageBreak,Iframe,Unlink,Anchor',
                    'extraPlugins' => 'autogrow',
                    'removePlugins' => 'resize, elementspath',
                    'autoGrow_onStartup' => true,
                    'autoGrow_minHeight' => '200',
                    'skin' => 'bootstrapck'
                ],

            ]) ?>
        </div>
    </div>

    <div class="buy-list-item">
        <div class="buy-list-icon">
            <div class="icon-conclusion" style=""></div>
            <div class="text-content <?= ($model->hasErrors('preparation_purchase')) ? 'has-error' : '' ?>">
                <label class="control-label">Заключение</label>
                <br><span class="colortext">Если речь идет о функциональном товаре:</span> Удобен ли товар в использовании? Насколько Вы довольны покупкой?
                <br><span class="colortext">Если речь идет о декоративном товаре:</span> Как товар выглядит в интерьере? Насколько Вы довольны покупкой?
            </div>
        </div>
        <div class="ckeditor-journal-buy">
            <?= $form->field($model, 'conclusion')->widget(CKEditor::className(), [

                'preset' => 'custom',
                'clientOptions' => [
                    'toolbarGroups' => [
                        ['name' => 'links', 'groups' => ['basicstyles', 'colors', 'insert', 'list']],
                    ],
                    'removeButtons' => 'Strike,Subscript,Superscript,Image,Flash,Table,HorizontalRule,SpecialChar,PageBreak,Iframe,Unlink,Anchor',
                    'extraPlugins' => 'autogrow',
                    'removePlugins' => 'resize, elementspath',
                    'autoGrow_onStartup' => true,
                    'autoGrow_minHeight' => '200',
                    'skin' => 'bootstrapck'
                ],

            ]) ?>
        </div>
    </div>

    <div class="buy-list-item">
        <div class="buy-list-icon">
            <div class="icon-advice" style=""></div>
            <div class="text-content">
                <label class="control-label">Советы другим</label>
                <br>Что можете порекомендовать новичкам, которым только предстоит выбор такого товара?
            </div>
        </div>
        <div class="ckeditor-journal-buy">
            <?= $form->field($model, 'advice')->widget(CKEditor::className(), [

                'preset' => 'custom',
                'clientOptions' => [
                    'toolbarGroups' => [
                        ['name' => 'links', 'groups' => ['basicstyles', 'colors', 'insert', 'list']],
                    ],
                    'removeButtons' => 'Strike,Subscript,Superscript,Image,Flash,Table,HorizontalRule,SpecialChar,PageBreak,Iframe,Unlink,Anchor',
                    'extraPlugins' => 'autogrow',
                    'removePlugins' => 'resize, elementspath',
                    'autoGrow_onStartup' => true,
                    'autoGrow_minHeight' => '200',
                    'skin' => 'bootstrapck'
                ],

            ]) ?>
        </div>
    </div>

    <div id="info-modal-buy" class="buy-list-item add_info" style="<?= $model["additional_information"] == "" ? "display:none" : "" ?>">
        <div class="buy-list-icon">
            <div class="icon-additional-information" style=""></div>
            <div class="text-content">
                <label class="control-label">Дополнительная информация</label>
                <br>Здесь Вы можете написать любую другую информацию о покупке, которой хотите поделиться с участниками и Леруа Мерлен
            </div>
        </div>
        <div class="ckeditor-journal-buy">
            <div class="close-form">
                <?= Html::a('<div class="icon-close" style=""></div>', '#', [ 'class' => 'text-success info-modal-btn', 'onclick' => 'openAddInfo(this)', 'data-target'=>'#info-modal-buy'])?>
            </div>
            <?= $form->field($model, 'additional_information')->widget(CKEditor::className(), [
                'preset' => 'custom',
                'clientOptions' => [
                    'toolbarGroups' => [
                        ['name' => 'links', 'groups' => ['basicstyles', 'colors', 'insert', 'list']],
                    ],
                    'removeButtons' => 'Strike,Subscript,Superscript,Image,Flash,Table,HorizontalRule,SpecialChar,PageBreak,Iframe,Unlink,Anchor',
                    'extraPlugins' => 'autogrow',
                    'removePlugins' => 'resize, elementspath',
                    'autoGrow_onStartup' => true,
                    'autoGrow_minHeight' => '200',
                    'skin' => 'bootstrapck'
                ],
            ]) ?>
        </div>
    </div>

    <div class="journal-buy-edit-info" style="<?= $model["additional_information"] != "" ? "display:none" : "" ?>">
            <span class="journal-edit-btn info-buy">
                <?= Html::a('<div class="icon-plus" style=""></div>'.'&nbsp;'.'Добавить текстовый блок', '#', [
                    'class' => 'text-success info-modal-btn', 'onclick' => 'openAddInfo(this)', 'data-target'=>'#info-modal-buy',
                ]) ?>
            </span>
    </div>

</div>

<script>
    function openAddInfo(element) {
        if (!$('.add_info').is(':visible')) {
            $('.journal-buy-edit-info').hide();
        }
        else {
            $('.journal-buy-edit-info').show();
        }
    }
</script>