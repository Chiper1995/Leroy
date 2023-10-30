<?php

use common\rbac\Rights;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $parentTheme \common\models\ForumTheme */

?>

<?php if ((($parentTheme != null) && ($parentTheme->parentTheme != null)) || ($dataProvider->count > 0)): ?>
<div class="row">
    <div class="col-md-12">
        <?php if ($dataProvider->count > 0):?>
            <?php $header = $this->render('_messagesThemesListHeader', ['parentTheme' => $parentTheme, 'canEdit' => Yii::$app->user->can(Rights::CREATE_FORUM_THEME, ['parentTheme' => $parentTheme])]); ?>
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_messagesThemesListItem',
                'viewParams' => ['canEdit' => Yii::$app->user->can(Rights::CREATE_FORUM_THEME, ['parentTheme' => $parentTheme])],
                'layout' => '<div class="forum-theme-table">'.$header.'{items}<div style="display: table-row" class="text-center">{pager}</div></div>',
                'itemOptions' => ['class' => 'forum-theme-table-row'],
            ]); ?>
        <?php else:?>
            <div class="no-themes">
                <p style="font-size: 18px; padding-top: 10px;">В этой теме пока нет обсуждений</p>
            </div>
        <?php endif;?>
    </div>
</div>
<?php endif;?>