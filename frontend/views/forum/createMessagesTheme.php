<?php
use \yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\forum\ForumMessagesThemeForm */
/* @var $parentTheme common\models\ForumTheme */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = 'Новое обсуждение';

$parentThemesNames = [];
$breadcrumbs = [$this->title];

$_parentTheme = $parentTheme;
$breadcrumbs[] = ['label' => $_parentTheme->name, 'url' => [$_parentTheme->is_messages_theme ? 'messages' : 'index', 'id' => $_parentTheme->id]];
while ($_parentTheme->parentTheme != null) {
    $_parentTheme = $_parentTheme->parentTheme;
    $parentThemesNames[] = Html::encode($_parentTheme->name);
    $breadcrumbs[] = ['label' => $_parentTheme->name, 'url' => [$_parentTheme->is_messages_theme ? 'messages' : 'index', 'id' => $_parentTheme->id]];
}
$parentThemesNames[] = 'Клуб ремонта';
$breadcrumbs[] = ['label' => 'Клуб ремонта', 'url' => ['index']];
$parentThemesNames = array_reverse($parentThemesNames);
$breadcrumbs = array_reverse($breadcrumbs);

$this->params['breadcrumbs'] = $breadcrumbs;
?>

<div class="row">
    <div class="col-md-12">
        <div class="small-header"><?= implode(' &rarr; ', $parentThemesNames)?></div>
        <h1><?= ($parentTheme->parentTheme == null) ? 'Клуб ремонта' : Html::encode($parentTheme->name); ?></h1>
    </div>
</div>

<?= $this->render('_messagesThemeForm', [
    'model' => $model,
    'parentTheme' => $parentTheme,
]) ?>

