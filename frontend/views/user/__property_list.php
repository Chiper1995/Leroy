<?php
/**
 * View для отображения списка, используется в информации о пользователе и в активации пользователя
 *
 * @var [] $items
 */
use yii\bootstrap\Html;

if (count($items) > 0) {
    $result = '';
    foreach($items as $item) {
        $result .= Html::tag('li', $item);
    }
    $result = Html::tag('ul', $result);
}
else
    $result = null;

echo $result;