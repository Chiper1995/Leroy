<?php
/**
 * @var $this yii\web\View
 * @var \frontend\models\journal\FamilyJournalSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \common\models\User $family
 */

use common\rbac\Rights;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = 'Дневник семьи: ' . ($family->family_name ? $family->family_name : $family->fio);
$this->params['breadcrumbs'][] = $this->title;

$familyViewAllowed = \Yii::$app->user->can(Rights::SHOW_FAMILIES);
?>
<div class="row family-journal-header" style="padding-top: 5px;">
    <div class="im">
        <?php if ($familyViewAllowed):?><a href="<?= Url::to(['user/family-view', 'id' => $family->id, 'returnUrl'=>Yii::$app->request->url])?>"><?php endif;?>
            <?=\frontend\widgets\UserPhoto\UserPhoto::widget(['user'=>$family, 'size'=>166])?>
        <?php if ($familyViewAllowed):?></a><?php endif;?>
    </div>
    <div class="memo">
        <h1 class="text-underline"><?= $familyViewAllowed
                ? Html::a(Html::encode($family->family_name), ['user/family-view', 'id' => $family->id, 'returnUrl'=>Yii::$app->request->url])
                : Html::encode($family->family_name); ?></h1>
        <?php if (!$familyViewAllowed):?>
            <p><b><?= Html::encode($family->fio); ?></b></p>
        <?php endif;?>
        <p><b>Вступили в проект:</b> <?= Yii::$app->formatter->format($family->created_at, 'date')?></p>
        <?php if ($familyViewAllowed):?>
            <p><b>Потратили на ремонт:</b> <?= Yii::$app->formatter->format(doubleval($family->totalSpent), ['decimal', 2])?> <i class="fa fa-rub"></i></p>
            <p><b>Накоплено:</b> <?= intval($family->points)?></p>
            <p>
                <?= Html::a(
                        Html::tag('i', '', ['class'=>'fa fa-file-pdf-o']).'&nbsp;'.'Сохранить дневник в PDF',
                        ['export-family-journal-to-pdf', 'id'=>$family->id],
                        ['class' => 'btn btn-primary', 'target'=>'_blank']
                ) ?>
            </p>
        <?php endif;?>
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
        <?= $this->render('../_journalListView', ['dataProvider' => $dataProvider, 'viewOnly' => false, 'showAuthor' => false]) ?>
    </div>
<?php endif;?>