<?php
/* @var \yii\web\View $this */
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

/* @var \common\models\notifications\JournalAddCommentNotification $notification */
$subjectJournalNotice = ArrayHelper::getValue($notification, 'journalComment.journal.subject');
$idJournalNotice = ArrayHelper::getValue($notification, 'journalComment.journal.id');
if (!empty($notification->journalComment)) {
?>

<li class="notification JournalAddCommentNotification" data-count = "<?= $notification->count ?>">
    <div class="icons">
        <p class="icon" style="">
            <?=Html::icon('comment')?>
        </p>
    </div>
    <div class="content">
        <div>
            <h4><?= \Yii::t('app', '{n, plural, one{Новый комментарий} few{У вас # новых комментария} many{У вас # новых комментариев} other{У вас # новых комментариев}}', ['n' => $notification->count]) ?></h4>
            <p><b>Тема: </b><span title="<?= Html::encode($subjectJournalNotice) ?>"><?= Html::encode($subjectJournalNotice) ?></span></p>
            <p>
                <b><?= $notification->countCommentUser > 1 ? 'Добавили' : 'Добавил' ?>: </b>
                <?= $notification->countCommentUser > 1 ?
                    \Yii::t('app', '{n, plural, one{# человек} few{# человека} many{# человек} other{# человек}}', ['n' => $notification->countCommentUser]) :
                    Html::encode($notification->journalComment->user->fio) ?>
            </p>
            <p style="padding-bottom: 4px"><?= Html::a('Подробнее &rarr;', [
                    'notification/show',
                    'id'=>$notification->id,
                    'url'=>\yii\helpers\Url::to([
                        'journal/view',
                        'id' => $idJournalNotice,
                        'returnUrl'=>Yii::$app->request->url,
                        '#'=>'comment-'.$notification->journalComment->id,
                    ])
                ], ['class' => 'btn btn-primary', 'style' => 'width: 150px;']) ?></p>
        </div>
    </div>
    <div class="close-notification">
        <?= Html::a(Html::img('/css/img/close-popup.png'), ['notification/close', 'id' => $notification->id, 'type' => "JournalAddCommentNotification"], [
            'class' => 'close notification-close'
        ])?>
    </div>
</li>
<?php
}
?>
