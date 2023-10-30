<?php

/**@var \common\models\ForumTheme $model*/
/* @var bool $canEdit */

use yii\bootstrap\Html;
use yii\helpers\Url;

?>


<div class="col-md-7 column-with-icon">
    <div class="im">
        <a href="<?= Url::to(['forum/index', 'id'=>$model->id, 'returnUrl'=>Yii::$app->request->url])?>" data-pjax="0">
            <?= Html::tag('div', Html::tag('i', '', ['class' => 'fa fa-comments-o']),['class' => 'img-circle']); ?>
        </a>
    </div>
    <?= Html::a(Html::encode($model->name), ['forum/index', 'id'=>$model->id, 'returnUrl'=>Yii::$app->request->url]); ?>
    <?php if (strlen($model->description) > 0):?>
        <br/><span class="desc"><?=Html::encode($model->description); ?></span>
    <?php endif;?>
</div>
<div class="col-md-2 hidden-sm hidden-xs text-center">
    <?=$model->themesCount()?>
</div>
<div class="col-md-3 column-with-buttons<?php if(!$canEdit):?> hidden-sm hidden-xs<?php endif;?>">
    <?php if ($model->lastMessage != null):?>
        <small class="hidden-sm hidden-xs"><b><?=Html::encode(($model->lastMessage->user->family_name != "") ? "{$model->lastMessage->user->family_name} ({$model->lastMessage->user->fio})" : $model->lastMessage->user->fio); ?></b></small><br/>
        <small class="hidden-sm hidden-xs"><?=Yii::$app->formatter->format($model->lastMessage->updated_at, ['date', 'dd.MM.Y HH:mm'])?></small>
    <?php endif;?>
    <?php if($canEdit):?>
        <div class="edit-buttons">
            <?= Html::a(Html::icon('pencil'), ['update', 'id'=>$model->id, 'returnUrl'=>Yii::$app->request->url], ['class' => 'btn btn-primary btn-xs',]) ?><br>
            <?= Html::a(Html::icon('trash'), ['delete', 'id'=>$model->id, 'returnUrl'=>Yii::$app->request->url], ['class' => 'btn btn-danger btn-xs', 'style'=>'margin-top: 6px;', 'data' => ['method'=>'post', 'pjax' => '0', 'confirm'=>'Вы уверены, что хотите удалить эту тему?'],]) ?>
        </div>
    <?php endif;?>
</div>

