<?php
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Html;

/* @var \yii\web\View $this */
/* @var array $buttons */
/* @var array $items */
/* @var string $selectedText */
/* @var string $cssClass */
/* @var string $btnCssClass */
?>

<div class="<?=$cssClass?>">
    <?= ButtonGroup::widget(['buttons' => $buttons, 'options' => ['class' => $cssClass.'-buttons',]]); ?>

    <div class="dropdown visible-xs">
        <?= Html::a($selectedText.' <b class="caret"></b>', '#', ['class'=>$btnCssClass.' dropdown-toggle', 'data'=>['toggle'=>'dropdown']]);?>
        <?= \yii\bootstrap\Dropdown::widget(['items' => $items,]);?>
    </div>
</div>