<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\journal\MySubscriptionSearch */

use common\models\Journal;
use frontend\widgets\StatusButtonsFilter\StatusButtonsFilterJournal;
use frontend\widgets\SubscribeLink\SubscribeLink;
use yii\bootstrap\Html;
use yii\web\View;
use yii\widgets\ListView;

$this->title = 'Мое избранное';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php \yii\widgets\Pjax::begin(); ?>
<div class="row">
    <div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
        <?= StatusButtonsFilterJournal::widget(['selectedStatus' => $searchModel->status, 'statusList' => Journal::getAllStatusJournal(), 'route' => 'journal/my-subscription'])?>
    </div>
</div>
<div class="row" style="padding-top: 5px;">
    <div class="col-md-offset-2 col-sm-offset-2 col-md-8 col-sm-8 content-container text-center <?= $dataProvider->count !== 0 ? 'hidden' : '' ?>">
        <p style="font-size: 18px; padding-top: 10px;">:(<br/>Пока нет записей</p>
    </div>

    <?= $this->render('_journalListView', ['dataProvider' => $dataProvider, 'viewOnly' => true, 'showAuthor' => true, 'removeFavorite' => $searchModel->status == Journal::FAVORITE_POST]) ?>
</div>
<?php \yii\widgets\Pjax::end(); ?>

