<?php
/* @var $this yii\web\View */
use yii\bootstrap\Html;

/* @var $model common\models\settings\SettingsRewards */
/* @var $settingsModel common\models\settings\Settings */

$this->title = $settingsModel->rus_name;
$this->params['breadcrumbs'][] = ['label' => 'Настройки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $settingsModel->rus_name];
?>

    <div class="settings">
        <h1><?= Html::encode($this->title) ?></h1>

        <?= $this->render('models/'.$settingsModel->name, array('model'=>$model)); ?>
    </div>