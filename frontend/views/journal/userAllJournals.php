<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\journal\UserAllJournalSearch */

use yii\bootstrap\Html;

$this->title = 'Лента';
$this->params['breadcrumbs'][] = $this->title;

$newSearchModel = Yii::$app->user->can(\common\rbac\Rights::SHOW_JOURNAL_SMART_SEARCH)
	? new \frontend\models\journal\AllJournalSmartSearch()
	: new \frontend\models\journal\UserAllJournalSmartSearch();
$newSearchModel->typeFilter = 'userJournals';
?>
<?php echo \frontend\widgets\StartGuide\StartGuide::widget();?>
<!-- Кнопка "Добавить запись" -->
<?php if (Yii::$app->user->can(\common\rbac\Rights::SHOW_MY_JOURNAL_RECORDS)): ?>
    <div class="row" style="padding-top: 5px;">
    <?php if ($dataProvider->count == 0):?>
        <div class="col-md-offset-2 col-sm-offset-2 col-md-8 col-sm-8" style="margin-bottom: 20px;">
            <?= Html::a(Html::icon('plus').'&nbsp;'.'Добавить запись', ['journal/create'], ['class' => 'btn btn-primary',]) ?>
        </div>
    <?php else:?>
        <div class="col-md-12" style="margin-bottom: 20px;">
            <?= Html::a(Html::icon('plus').'&nbsp;'.'Добавить запись', ['journal/create'], ['class' => 'btn btn-primary',]) ?>
        </div>
    <?php endif;?>
    </div>
<?php endif; ?>


<div class="row" style="padding-top: 5px;">
    <?php if ($dataProvider->count == 0):?>
        <div class="col-md-offset-2 col-sm-offset-2 col-md-8 col-sm-8 content-container text-center">
            <p style="font-size: 18px; padding-top: 10px;">:(<br/>Пока нет записей</p>
        </div>
    <?php else:?>
        <!-- инпут поиска -->
		<div class="container">
			<?= $this->render('//journal/smart-search/_filter', ['searchModel' => $newSearchModel]); ?>
		</div>

        <!-- фильтры -->
        <?php if (Yii::$app->user->can(\common\rbac\Rights::FILTER_FEED_CURATOR)): ?>
            <?= $this->render('_filterFeedCurator', ['searchModel' => $searchModel]); ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can(\common\rbac\Rights::FILTER_FEED_USER)): ?>
            <?= $this->render('_filterFeedUser', ['searchModel' => $searchModel]); ?>
        <?php endif; ?>

        <!-- лента записей -->
        <?= $this->render('allJournalListView', ['dataProvider' => $dataProvider, 'viewOnly' => true, 'showAuthor' => true]) ?>
    <?php endif;?>
</div>

