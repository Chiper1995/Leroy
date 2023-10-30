<?php

use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $messagesDataProvider yii\data\ActiveDataProvider */

?>

<div class="row">
    <div class="col-md-12">
        <div class="forum-messages-thumbnails">
            <?= ListView::widget([
                'dataProvider' => $messagesDataProvider,
                'itemView' => '_messagesListItem',
                'layout' => '{items}<div class="row"><div class="col-md-12"><div class="text-center">{pager}</div></div></div>',
            ]); ?>
        </div>
    </div>
</div>
