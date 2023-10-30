<?php

use common\rbac\Rights;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $themesDataProvider yii\data\ActiveDataProvider */
/* @var $themesSearchModel frontend\models\forum\ForumThemeSearch */
/* @var $parentTheme \common\models\ForumTheme */
/* @var $messagesThemesSearchModel frontend\models\forum\ForumMessagesThemeSearch */
/* @var $messagesThemesDataProvider yii\data\ActiveDataProvider */

if ($parentTheme == null) {
    $this->title = 'Клуб ремонта';
    $this->params['breadcrumbs'][] = $this->title;
}
else {
    $parentThemesNames = [];
    $breadcrumbs = [];

    $_parentTheme = $parentTheme;
    $breadcrumbs[] = $_parentTheme->name;
    while ($_parentTheme->parentTheme != null) {
        $_parentTheme = $_parentTheme->parentTheme;
        $parentThemesNames[] = $_parentTheme->name;
        $breadcrumbs[] = ['label' => $_parentTheme->name, 'url' => [$_parentTheme->is_messages_theme ? 'messages' : 'index', 'id' => $_parentTheme->id]];
    }
    $parentThemesNames[] = 'Клуб ремонта';
    $breadcrumbs[] = ['label' => 'Клуб ремонта', 'url' => ['index']];

    $this->title = implode(' | ', \yii\helpers\ArrayHelper::merge([$parentTheme->name], $parentThemesNames));

    $parentThemesNames = array_reverse($parentThemesNames);
    $breadcrumbs = array_reverse($breadcrumbs);

    $this->params['breadcrumbs'] = $breadcrumbs;

}

$this->context->layout = 'mainEmpty';
?>
<div class="row">
    <div class="col-md-8 col-sm-8 col-xs-8">
        <?php if ($parentTheme != null):?>
        <div class="small-header">Клуб ремонта<?= ($parentTheme->parentTheme == null) ? '' : ' &rarr; '.Html::encode($parentTheme->parentTheme->name); ?></div>
        <?php endif;?>
        <h1><?= Html::encode(($parentTheme == null) ? 'Клуб ремонта' : $parentTheme->name) ?></h1>
    </div>
    <?php if (Yii::$app->user->can(Rights::CREATE_FORUM_THEME, ['parentTheme' => $parentTheme])):?>
    <div class="col-md-4 col-sm-4 col-xs-4 text-right">
        <?php if (($parentTheme != null) && ($parentTheme->parentTheme != null)):?>
            <?= Html::a(Html::icon('plus').'&nbsp;'.'Новое обсуждение', ['create-message-theme', 'id'=>$parentTheme->id, 'returnUrl'=>Yii::$app->request->url], ['class' => 'btn btn-primary', 'style'=>'float:right']) ?>
        <?php else:?>
            <?= Html::a(Html::icon('plus').'&nbsp;'.'Новая тема', (($parentTheme == null) ? ['create'] : ['create', 'id'=>$parentTheme->id, 'returnUrl'=>Yii::$app->request->url]), ['class' => 'btn btn-primary', 'style'=>'float:right']) ?>
        <?php endif;?>
    </div>
    <?php endif;?>
</div>
<?php if (!(($parentTheme != null) && ($parentTheme->parentTheme != null))):?>
    <?= $this->render('_themesList', ['dataProvider' => $themesDataProvider, 'parentTheme' => $parentTheme])?>
<?php endif;?>
<?= $this->render('_messagesThemesList', ['dataProvider' => $messagesThemesDataProvider, 'parentTheme' => $parentTheme])?>
