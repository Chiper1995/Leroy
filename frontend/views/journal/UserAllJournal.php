<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\MyJournalSearch */

use yii\bootstrap\Html;

$this->title = 'Мои записи';
$this->params['breadcrumbs'][] = $this->title;
?>

    <div class="row" style="padding-top: 5px;">
        <?php if ($dataProvider->count == 0):?>
            <div class="col-md-offset-2 col-sm-offset-2 col-md-8 col-sm-8 content-container text-center">
                <p style="font-size: 18px;">У тебя пока нет записей, самое время написать первую</p>
                <div>
                    <?= Html::a(Html::icon('plus', ['tag'=>'i']).'&nbsp;'.'Добавить запись', ['create'], ['class' => 'btn btn-primary', 'style' => 'width: 230px;']) ?>
                </div>
            </div>
        <?php else:?>
            <div class="col-md-12" style="margin-bottom: 20px;">
                <?= Html::a(Html::icon('plus', ['tag'=>'i']).'&nbsp;'.'Добавить запись', ['create'], ['class' => 'btn btn-primary', 'style' => 'width: 230px;']) ?>
            </div>
            <?= $this->render('_journalListView', ['dataProvider' => $dataProvider, 'viewOnly' => false]) ?>
        <?php endif;?>
    </div>