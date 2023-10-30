<?php

/* @var $this yii\web\View */

/* @var $form yii\bootstrap\ActiveForm */

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

?>
<div class="row">
    <div class="col-md-12 text-center" style="margin-bottom: 20px;">
        Для предоставления доступа к платформе «Семьи Леура Мерлен», пожалуйста, ответь на несколько вопросов:
    </div>
</div>
<div class="row col-md-12">
    Где ты работаешь?
    <?php echo Html::beginForm(['employee/place'], 'post', ['data-pjax' => '', 'class' => 'form-horizontal']); ?>
    <?php
    echo Html::radioList('placeOfWork', 'office',
        [
            'office' => 'В центральном офисе',
            'shop' => 'В магазине'
        ],
        [
            'item' => function ($index, $label, $name, $checked, $value) {
                $id = 'place-' . $value;
                return
                    Html::beginTag('div', ['class' => 'radio']) .
                    Html::radio($name, $checked, ['value' => $value, 'id' => $id, 'label' => $label]) .
                    Html::endTag('div');
            },
        ]);
    ?>
    <div class="form-group text-center form-buttons">
        <?php echo Html::submitButton('Далее &rarr;', ['class' => 'btn btn-primary', 'name' => 'next', 'style' => 'width: 180px;']) ?>
    </div>
    <?php echo Html::endForm() ?>
</div>

