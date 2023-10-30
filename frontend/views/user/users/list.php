<?php

use common\rbac\Rights;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\user\UserSearch */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <div class="row">
        <div class="col-md-8 col-sm-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-4 col-sm-4 text-right">
            <?= Html::a(Html::icon('plus').'&nbsp;'.'Добавить', ['create'], ['class' => 'btn btn-primary',]) ?>
        </div>
    </div>

    <?php \yii\widgets\Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'visible' => \Yii::$app->user->can(Rights::SHOW_ID_COLUMNS),
                'headerOptions' => [
                    'style' => 'width: 80px;'
                ]
            ],

            'fio',
            'username',
            [
                'attribute' => 'role',
                'value' => function ($model){
                    return ArrayHelper::getValue(\common\models\User::getUserRoleList(), $model->role, "");
                },
                'filter' => \kartik\widgets\Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'role',
                    'data' => \common\models\User::getUserRoleList(),
                    'pluginOptions' => ['allowClear' => false,],
                    'options' => ['multiple' => true],
                ]),
                'headerOptions' => [
                    'style' => 'width: 190px;'
                ]
            ],
            [
                'format' => 'html',
                'header' => 'Город',
                'value' => function($model) {return implode(',<br/>', \yii\helpers\ArrayHelper::getColumn($model->cities, 'name'));},
                'filter' => \kartik\widgets\Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'city_id',
                    'data' => \common\models\City::getList(),
                    'pluginOptions' => ['allowClear' => false,],
                    'options' => ['multiple' => true],
                ]),
                'headerOptions' => [
                    'style' => 'width: 180px;'
                ]
            ],
            [
                'attribute' => 'updated_at',
                'format' =>  ['date', 'dd.MM.Y HH:mm:ss'],
                'headerOptions' => [
                    'style' => 'width: 180px;'
                ]
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update}{delete}{reset-notifications}{login-as}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    $params = is_array($key) ? $key : ['id' => (string) $key];
                    $params['returnUrl'] = Yii::$app->request->url;
                    $params[0] = $action;

                    return Url::toRoute($params);
                },
                'buttons' => [
                    'reset-notifications' => function ($url, $model, $key) {
                        $cnt = $model->getNewNotifications()->count();
                        if (\Yii::$app->user->can(Rights::FAMILY_RESET_PASSWORD)) {
                            $options = [
                                'title' => 'Сбросить уведомления ('.$cnt.')',
                                'aria-label' => 'Сбросить уведомления ('.$cnt.')',
                                'data' => ['method'=>'post', 'pjax' => '0', 'toggle' => 'tooltip', 'placement' => 'bottom', 'confirm'=>'Вы уверены, что хотите сбросить уведомления ('.$cnt.')?'],
                                'class' => 'grid-button'
                            ];
                            return Html::a('<span class="glyphicon glyphicon-check"></span>', $url, $options);
                        }
                        else {
                            return '';
                        }
                    },
                    'login-as' => function ($url, $model, $key) {
                        if (\Yii::$app->user->can(Rights::USER_LOGIN_AS)) {
                            $options = [
                                'title' => 'Войти как этот пользователь',
                                'aria-label' => 'Войти как этот пользователь',
                                'data' => ['method'=>'post', 'pjax' => '0', 'toggle' => 'tooltip', 'placement' => 'top', 'confirm'=>'Вы уверены, что хотите войти как этот пользователь?'],
                                'class' => 'grid-button'
                            ];
                            return Html::a('<span class="glyphicon glyphicon-sunglasses"></span>', $url, $options);
                        }
                        else {
                            return '';
                        }
                    },
                ]
            ],
        ],
    ]); ?>
    <?php \yii\widgets\Pjax::end(); ?>

</div>
