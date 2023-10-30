<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\AllJournalSearch */

use common\models\Journal;
use frontend\widgets\StatusButtonsFilter\StatusButtonsFilter;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Дневники';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="row" style="padding-top: 5px;">
    <div class="col-md-12">
        <?= StatusButtonsFilter::widget(['selectedStatus' => $searchModel->status, 'statusList' => Journal::getAllStatusNamesList(), 'route' => 'journal/all-journals'])?>
    </div>
</div>
<?php if ($dataProvider->count == 0):?>
    <div class="row" style="padding-top: 5px;">
        <div class="col-md-offset-2 col-sm-offset-2 col-md-8 col-sm-8 content-container text-center">
            <p style="font-size: 18px; padding-top: 10px;">:(<br/>Пока нет записей</p>
        </div>
    </div>
<?php else:?>
    <div class="row" style="padding-top: 5px;">
        <?= $this->render('_journalListView', ['dataProvider' => $dataProvider, 'viewOnly' => false, 'showAuthor' => true]) ?>
    </div>
<?php endif;?>

