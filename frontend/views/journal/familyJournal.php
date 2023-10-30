<?php
/**
 * @var $this yii\web\View
 * @var \frontend\models\FamilyJournalSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \common\models\User $family
 */

use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = 'Дневник семьи: '.$family->fio;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row family-journal-header" style="padding-top: 5px;">
    <div class="im">
        <a href="<?= Url::to(['user/family-view', 'id' => $family->id, 'returnUrl'=>Yii::$app->request->url])?>">
            <?=\frontend\widgets\UserPhoto\UserPhoto::widget(['user'=>$family, 'size'=>166])?>
        </a>
    </div>
    <div class="memo">
        <h1><?= Html::a($family->family_name, ['user/family-view', 'id' => $family->id, 'returnUrl'=>Yii::$app->request->url])?></h1>
        <p><b>Вступили в проект:</b> <?= Yii::$app->formatter->format($family->created_at, 'date')?></p>
        <p><b>Потратили на ремонт:</b> <?= Yii::$app->formatter->format(doubleval($family->totalSpent), ['decimal', 2])?> <i class="fa fa-rub"></i></p>
        <p><b>Заработали баллов:</b> <?= intval($family->points)?></p>
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
        <?= $this->render('_journalListView', ['dataProvider' => $dataProvider, 'viewOnly' => false, 'showAuthor' => false]) ?>
    </div>
<?php endif;?>