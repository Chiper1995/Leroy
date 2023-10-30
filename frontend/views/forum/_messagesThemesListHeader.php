<?php

use common\rbac\Rights;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $parentTheme \common\models\ForumTheme */
/* @var bool $canEdit */

?>

<div class="forum-theme-table-header">
    <div class="col-md-7">
        Обсуждения
    </div>
    <div class="col-md-2 hidden-sm hidden-xs text-center">
        Сообщения
    </div>
    <div class="col-md-3<?php if(!$canEdit):?> hidden-sm hidden-xs<?php endif;?>">
        <span class="hidden-sm hidden-xs">Последнее сообщение</span>
    </div>
</div>