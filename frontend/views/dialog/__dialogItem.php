<?php
use yii\bootstrap\Html;

/**@var Dialog $model*/

use common\models\Dialog;
use yii\helpers\Url;

?>

<div class="media dialog-item">
    <a href="<?= Url::toRoute(['view-dialog', 'dialogId'=>$model->id])?>">
        <div class="media-left">
            <?=\frontend\widgets\UserPhoto\UserPhoto::widget(['user'=>$model->author, 'size'=>60])?>
        </div>
        <div class="media-body">
            <div class="dialog-subject"><?= Html::encode($model->subject); ?></div>
            <div class="row">
                <div class="col-md-4">
                    <div class="dialog-desc">
                        <div><?= (Yii::$app->user->id == $model->author->id) ? 'Я' : Html::encode($model->author->fio).' ('.Html::encode($model->author->username).')' ?></div>
                        <div class="dialog-user-count">
                            <?= \Yii::t('app', '{n, plural, one{# участник} few{# участника} many{# участников} other{# участников}}', ['n' => $model->getUsers()->count()]); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="dialog-last-message<?=(($model->getNewMessagesCount(Yii::$app->user->id) > 0) ? ' new' : '')?>">
                        <div class="media">
                            <div class="media-left">
                                <?=\frontend\widgets\UserPhoto\UserPhoto::widget(['user'=>$model->lastMessage->user, 'size'=>32])?>
                            </div>
                            <div class="media-body">
                                <div class="dialog-last-message-text"><?=$model->lastMessage->message?></div>
                                <?php echo Html::tag('b',Html::encode($model->lastMessage->created_at),['id'=>'timestamp','style'=>'display: none;']);?>
                                <div class="dialog-last-message-time">
                                   <!-- //=Yii::$app->formatter->format($model->lastMessage->created_at, ['date', 'dd.MM.Y HH:mm:ss']) -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>
