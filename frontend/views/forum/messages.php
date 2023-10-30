<?php

use common\rbac\Rights;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $messagesDataProvider yii\data\ActiveDataProvider */
/* @var $messageSearchModel frontend\models\forum\ForumMessageSearch */
/* @var $parentTheme \common\models\ForumTheme */

$parentThemesNames = [];
$breadcrumbs = [];

$_parentTheme = $parentTheme;
$breadcrumbs[] = $_parentTheme->name;
while ($_parentTheme->parentTheme != null) {
    $_parentTheme = $_parentTheme->parentTheme;
    $parentThemesNames[] = Html::encode($_parentTheme->name);
    $breadcrumbs[] = ['label' => $_parentTheme->name, 'url' => [$_parentTheme->is_messages_theme ? 'messages' : 'index', 'id' => $_parentTheme->id]];
}
$parentThemesNames[] = 'Клуб ремонта';
$breadcrumbs[] = ['label' => 'Клуб ремонта', 'url' => ['index']];

$this->title = implode(' | ', \yii\helpers\ArrayHelper::merge([$parentTheme->name], $parentThemesNames));

$parentThemesNames = array_reverse($parentThemesNames);
$breadcrumbs = array_reverse($breadcrumbs);

$this->params['breadcrumbs'] = $breadcrumbs;
?>

<div class="row">
    <div class="col-md-8 col-sm-8 col-xs-8">
        <div class="small-header"><?= implode(' &rarr; ', $parentThemesNames)?></div>
        <h1><?= Html::encode($parentTheme->name) ?></h1>
    </div>
    <?php if (Yii::$app->user->can(Rights::CREATE_FORUM_MESSAGE, ['parentTheme' => $parentTheme])):?>
    <div class="col-md-4 col-sm-4 col-xs-4 text-right">
        <?= Html::a(Html::icon('plus').'&nbsp;'.'Новое сообщение', '#create-message', ['class' => 'btn btn-primary', 'style'=>'float:right']) ?>
    </div>
    <?php endif;?>
</div>
<?= $this->render('_messagesList', ['messagesDataProvider' => $messagesDataProvider])?>

<?php if (Yii::$app->user->can(Rights::CREATE_FORUM_MESSAGE, ['parentTheme' => $parentTheme])):?>
    <?= $this->render('_createMessageForm', ['model' => $model, 'theme' => $parentTheme])?>
<?php endif;?>