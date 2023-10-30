<?php
use yii\bootstrap\Html;
?>

<div>
    Здравствуйте!<br/>
    Следующие кураторы не выходили на платформу более 14 дней:
    <br/>
    <?php foreach ($options['curators'] as $curator): ?>
        <?= Html::tag('p', Html::encode($curator['fio'])) ?>
    <?php endforeach; ?>
</div>
<br/>
<div>
    Позвони и напомни им о проекте, о необходимости общаться с Семьями!!<br/>
    <br/>
    Хранитель Семей
</div>
