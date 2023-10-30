<?php
use common\rbac\Rights;
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var \yii\web\View $this */
/* @var common\models\User $user */
?>
<div class="dropdown-menu">
    <div class="card-container">
        <div class="im">
            <a href="<?= Url::to(['/user/profile', 'returnUrl'=>Yii::$app->request->url])?>" data-pjax="0">
                <?=\frontend\widgets\UserPhoto\UserPhoto::widget(['user'=>$user, 'size'=>96])?>
            </a>
        </div>
        <div class="card-info">
            <p class="family-name"><?= Html::encode($user->family_name); ?></p>
            <p class="name"><?= Html::encode($user->fio); ?></p>
            <p class="login"><?= Html::encode($user->username); ?></p>
            <?php if(\Yii::$app->user->can(Rights::SHOW_POINTS)):?>
                <p class="spent">Потрачено: <?=Yii::$app->formatter->format(doubleval($user->totalSpent), ['decimal', 2])?> <i class="fa fa-rub"></i></p>
                <p class="points"><?= Html::icon('piggy-bank') ?> <span class="caption"><?=$user->points?> баллов</span></p>
            <?php endif;?>
			<?php if(\Yii::$app->session->get('PARENT_LOGGED_USER_ID') !== null): ?>
				<p>
					<?= Html::a('<i class="fa fa-sign-out"></i>&nbsp;&nbsp;Зайти обратно в свой аккаунт', ['/user/login-as-parent-logged-user'], ['class' => 'btn btn-primary btn-xs'])?>
				</p>
			<?php endif;?>
        </div>
    </div>
    <div class="buttons">
        <?php //ANDR Сделать получение request->url без дублирования ссылок?>
        <?= Html::a('<i class="fa fa-user"></i>&nbsp;&nbsp;Профиль', ['/user/profile', 'returnUrl'=>Yii::$app->request->url], ['class' => 'btn btn-primary'])?>
        <?= Html::a('<i class="fa fa-sign-out"></i>&nbsp;&nbsp;Выход', ['/site/logout'], ['class' => 'btn btn-primary pull-right'])?>
    </div>
</div>
