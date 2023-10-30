<?php
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

/** @var \yii\web\View $this */
/** @var \common\models\User $user */
/** @var integer $size */
/** @var boolean $showTitle */
/** @var boolean $showPosition */

?>

<?php
$titleOptions = [];
if ($showTitle) {
    $titleOptions['title'] = Html::encode($user->fio.' ('.$user->username.')');
    $titleOptions['data'] = ['toggle'=>'tooltip', 'placement'=>'bottom'];
}

$positionHtml = '';
if ($showPosition) {
    if (($position = ArrayHelper::getValue($user->getPosition(), $user->role, null)) !== null) {
        if ($size >= 60) {
            $positionHtml = Html::tag('div', Html::tag('span', $position['full'], ['class' => 'label label-default']), ['class' => 'position']);
        }
        else {
            $positionHtml = Html::tag('div', Html::tag('span', $position['short'], ['class' => 'badge badge-default']), ['class' => 'position']);
        }
    }
}

$photoHtml = '';
if (strlen($user->photo) > 0) {
    $photoHtml = Html::img($user->getPhotoThumb($size, $size), ArrayHelper::merge(['class'=>'img-circle'], $titleOptions));
}
else {
    $photoHtml = Html::tag('div', Html::icon('user'), ArrayHelper::merge(['class'=>'img-circle'], $titleOptions));
}
?>

<?php echo Html::tag('div', $photoHtml . $positionHtml, ['class' => 'user-photo-container', 'style' => 'width: '.$size.'px;']); ?>