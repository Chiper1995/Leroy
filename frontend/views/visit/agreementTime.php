<?php

/* @var $model common\models\Visit */
/* @var $this yii\web\View */

use common\models\Visit;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = 'Визит '.Yii::$app->formatter->asDate($model->date).', время: '. Visit::getAllTimeNamesList()[$model->time];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_view', [
    'model' => $model,
]) ?>

<?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-12 content-container" style="padding-bottom: 0; margin-top: 0px;">
            <div class="form-group form-buttons">
                <?= Html::submitButton(Html::icon('ok').'&nbsp;Согласовать', ['name' => 'agreed', 'class' => 'btn btn-primary btn-with-margin-right',]) ?>
                <?= Html::submitButton(Html::icon('remove').'&nbsp;Отказаться от визита', ['name' => 'canceled', 'class' => 'btn btn-danger btn-with-margin-right',]) ?>
                <?= Html::a('Отмена', Url::to(Yii::$app->user->getReturnUrl(['journal/all-journals'])), ['class' => 'btn btn-default',]) ?>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>