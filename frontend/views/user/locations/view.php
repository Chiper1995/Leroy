<?php
use yii\bootstrap\Html;
use kartik\widgets\Select2;
use frontend\widgets\FamilyLocations\FamilyLocations;
use common\models\City;

$this->title = 'Семьи на карте';
$defaultCity = City::find()->where(['name'=>'Москва'])->one()->id;
?>

<div>
    <div class="row">
        <div class="col-md-4">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= Select2::widget([
                'name' => 'city',
                'value' => $defaultCity,
                'data' => City::getList(),
                'options' => ['id' => 'select-city']
            ]); ?>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-12">
            <?= FamilyLocations::widget() ?>
        </div>
    </div>
</div>
