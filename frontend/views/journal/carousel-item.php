<?php

use common\models\JournalPhoto;
use yii\bootstrap\Html;

/* @var $model JournalPhoto */
?>

<?= Html::img($model->getPhotoThumb(1164, 760, false, true)); ?>

<div class="content-description carousel-control-description text-description-photo carousel-caption">
    <div class="text-container">
        <div class="text"><?= $model->description ?></div>
        <?php if (mb_strlen($model->description) > 0):?>
            <a class="cursor-pointer" onclick="toggleText(this)" data-label="Скрыть">Показать полностью</a>
        <?php endif;?>
    </div>
</div>

<script>
    function toggleText(element) {
        var container = element.closest('.text-container');
        var label = element.innerHTML;
        container.querySelector('.text').classList.toggle('fulltext');
        element.innerHTML = element.getAttribute('data-label');
        element.setAttribute('data-label', label);
    }
</script>