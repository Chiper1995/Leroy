<?php

use yii\web\View;
use yii\bootstrap\Html;
use frontend\assets\AppAsset;

/* @var \yii\web\View $this */

AppAsset::register($this);
\frontend\assets\FontModalAsset::register($this);
?>
<?php
$script = <<< JS
    $(document).mouseup(function (e){
        var div = $("#inner");
        if (!div.is(e.target) // если клик был не по нашему блоку
            && div.has(e.target).length === 0) { // и не по его дочерним элементам
            $("#popup").removeClass('show');
        }
    });
JS;
$this->registerJs($script, View::POS_READY);
?>

<div class="layout" id='popup'>
    <div class="layout__inner" id="inner">
            <span class="close-popup" onclick='$("#popup").removeClass("show");'>
                <?php echo Html::img('/css/img/close-popup.png') ?>
            </span>
        <h1>Добро пожаловать!</h1>
        <p>Спасибо, что присоединились к нашему проекту-исследованию!
            Рассказывайте, где покупаете товары для ремонта и как используете их в работе.</p>
        <p>Как начать вести свой дневник и о чем писать — читайте в <?php echo Html::a(Html::encode('«Справке»'), ['help/view'], ['class' => 'more-info']); ?></p>
        <p>На проекте нет правильных или неправильных решений – мы просто делимся опытом, даем советы и находим
            интересные идеи. Ваша честность и описание последовательности действий – залог успешной публикации.
            Талант блогера будет лишь приятным дополнением :)
            Вы всегда сможете задать вопросы куратору и администраторам - напишите пост или личное сообщение. </p>
        <p>Желаем успехов!</p>
        <?php echo Html::button('Начать писать свою уникальную историю ремонта!', ['class' => 'btn', 'onclick' => '$("#popup").removeClass("show");']); ?>
        <?php echo Html::img('/css/img/popup-footer-img.png', ['class' => 'popup_footer_img']) ?>
    </div>
</div>
