<?php

use common\models\Visit;
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Visit */

$this->title = "Визит к {$model->user->fio} ".Yii::$app->formatter->asDate($model->date).' в '. Visit::getAllTimeNamesList()[$model->time];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_view', [
    'model' => $model,
]) ?>

<div class="row">
    <div class="col-md-12 content-container" style="padding-bottom: 0;">
        <div class="form-group">
            <?= Html::a('Закрыть', Url::to(Yii::$app->user->getReturnUrl()), ['class' => 'btn btn-default',]) ?>
        </div>
    </div>
</div>

