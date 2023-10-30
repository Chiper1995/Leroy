<?php

use common\rbac\Rights;
use yii\bootstrap\Html;
use yii\helpers\Url;
use common\models\Journal;

/* @var $this yii\web\View */
/* @var $model common\models\Task */

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (Yii::$app->user->can(Rights::EDIT_TASKS, ['task'=>$model])):?>
    <div class="row">
        <div class="col-md-12 content-container" style="padding-bottom: 0; margin-bottom: 15px;">
            <div class="form-group">
                <?= Html::a(Html::icon('edit').' Редактировать задание', ['task/update', 'id'=>$model->id,], ['class' => 'btn btn-primary btn-with-margin-right',]) ?>
            </div>
        </div>
    </div>
<?php endif; ?>
<?= $this->render('_view', [
    'model' => $model,
]) ?>

<div class="row">
    <div class="col-md-12 content-container" style="padding-bottom: 0;">
        <div class="form-group">
            <?php if (Yii::$app->user->can(Rights::SHOW_TASKS_TO_ME, ['task'=>$model])):?>
                <?php $journal = $model->getFamilies()->andWhere(['user_id'=>Yii::$app->user->id])->one()->journal;?>
                <?php if ($journal != null):?>
                    <?= Html::a(Html::icon('edit').' Перейти к записи', ['journal/update', 'id'=>$journal->id,], ['class' => 'btn btn-primary btn-with-margin-right',]) ?>

                    <?php if ($journal->status == Journal::STATUS_DRAFT): ?>
                        <?= Html::a(Html::icon('remove').' Отказаться',
                            ['task/refuse', 'id'=>$model->id,],
                            [
                                'class' => 'btn btn-danger btn-with-margin-right',
                                'data-confirm' => 'У Вас уже есть черновик отказаться от задания и удалить его?',
                            ]
                        ) ?>
                    <?php endif; ?>
                <?php else:?>
                    <?= Html::a(Html::icon('edit').' Создать запись', ['journal/create-from-task', 'id'=>$model->id,], ['class' => 'btn btn-primary btn-with-margin-right',]) ?>
                    <?= Html::a(Html::icon('remove').' Отказаться',
                        ['task/refuse', 'id'=>$model->id,],
                        [
                            'class' => 'btn btn-danger btn-with-margin-right',
                            'data-confirm' => 'Вы уверены, что хотите отказаться от выполнения задания?',
                        ]
                    ) ?>
                <?php endif;?>
            <?php endif;?>
            <?= Html::a('Закрыть', Url::to(Yii::$app->user->getReturnUrl()), ['class' => 'btn btn-default',]) ?>
        </div>
    </div>
</div>

