<?php
namespace frontend\controllers;

use common\components\actions\CreateAction;
use common\components\actions\DeleteAction;
use common\components\actions\ListAction;
use common\components\actions\UpdateAction;
use common\components\actions\UploadImageAction;
use common\components\actions\ViewAction;
use common\components\controllers\BaseController;
use common\components\controllers\IModelController;
use common\models\Visit;
use common\models\VisitPhoto;
use common\rbac\Rights;
use frontend\actions\VisitCreateAction;
use frontend\actions\VisitUpdateAction;
use frontend\models\MyVisitsSearch;
use frontend\models\VisitSearch;
use ReflectionClass;
use Yii;
use yii\bootstrap\Html;
use yii\data\Sort;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;


class VisitController extends BaseController implements IModelController
{

    public $layout = 'mainEmpty';

    public function getModelClass()
    {
        return Visit::className();
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'create' => [
                'class' => CreateAction::className(),
                'access' => Rights::SHOW_VISITS,
                'returnUrl' => 'visit/index',
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'returnUrl' => 'visit/index',
                'access' => function($action, $model) {
                    /**@var Visit $model*/
                    return Yii::$app->user->can(Rights::EDIT_VISITS, ['visit'=>$model]);
                }
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'returnUrl' => 'visit/index',
                'access' => function($action, $model) {
                    /**@var Visit $model*/
                    return Yii::$app->user->can(Rights::EDIT_VISITS, ['visit'=>$model]);
                }
            ],
            'view' => [
                'class' => ViewAction::className(),
                'access' => function($action, $model) {
                    /**@var Visit $model*/
                    return
                        \Yii::$app->user->can(Rights::SHOW_VISITS, ['visit' => $model])
                        or \Yii::$app->user->can(Rights::SHOW_VISITS_TO_ME, ['visit' => $model]);
                },
            ],
            // Выборка визитов
            'index' => [
                'class' => ListAction::className(),
                'modelClass' => Visit::className(),
                'searchModelClass' => VisitSearch::className(),
                'view' => 'list',
                'access' => Rights::SHOW_VISITS,
                'dataProviderConfig' => [
                    'pagination' => [
                        'pageSize' => 20,
                        'defaultPageSize' => 20,
                    ],
                    'sort' => [
                        'attributes' => [
                            'id',
                            'status',
                            'date',
                            'time',
                            'user.fio',
                            'updated_at',
                        ],
                        'defaultOrder' => [
                            'date' => SORT_ASC,
                            'time' => SORT_ASC,
                            //'updated_at' => SORT_DESC,
                        ]
                    ]
                ]
            ],
            // Визиты ко мне
            'my-visits' => [
                'class' => ListAction::className(),
                'modelClass' => Visit::className(),
                'searchModelClass' => MyVisitsSearch::className(),
                'view' => 'myVisits',
                'access' => Rights::SHOW_VISITS_TO_ME,
                'dataProviderConfig' => [
                    'pagination' => [
                        'pageSize' => 10,
                        'defaultPageSize' => 10,
                    ],
                    'sort' => [
                        'attributes' => [
                            'date',
                            'time',
                        ],
                        'defaultOrder' => [
                            'date' => SORT_ASC,
                            'time' => SORT_ASC,
                        ]
                    ]
                ]
            ],

        ];
    }

    public function actionAgreement($id)
    {
        /**@var Visit $model*/
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if (($model = $model::findOne($id)) === null) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }
        $model->setScenario('agreement');

        // Проверка статуса
        if ($model->status != Visit::STATUS_ON_AGREEMENT) {
            Yii::$app->session->setFlash('error', Html::icon('alert') . ' Визит уже обработан');
            return $this->redirect([Yii::$app->user->getReturnUrl()]);
        }

        // Проверка доступа
        if (!Yii::$app->user->can(Rights::SHOW_VISIT_ON_AGREEMENT_NOTIFICATION, ['visit'=>$model]))
        {
            throw new ForbiddenHttpException($this->noAccessMessage);
        }

        if (Yii::$app->request->post('agreed') !== null) {
            $model->status = Visit::STATUS_AGREED;
            $model->save(false);
            return $this->redirect([Yii::$app->user->getReturnUrl()]);
        }

        if (Yii::$app->request->post('canceled') !== null) {
            $model->status = Visit::STATUS_CANCELED;
            $model->save(false);
            return $this->redirect([Yii::$app->user->getReturnUrl()]);
        }

        if (Yii::$app->request->post('changed_time') !== null) {
            if ($model->load(Yii::$app->request->post())) {
                if ((isset($model->dirtyAttributes['time']))and($model->oldAttributes['time']!=$model->time)) {
                    $model->status = Visit::STATUS_TIME_EDITED;
                    $model->save(false);
                }
                else {
                    $model->status = Visit::STATUS_AGREED;
                    $model->save(false);
                }
                return $this->redirect([Yii::$app->user->getReturnUrl()]);
            }
        }

        return $this->render('agreement', [
            'model' => $model,
        ]);
    }

    public function actionAgreementTime($id)
    {
        /**@var Visit $model*/
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if (($model = $model::findOne($id)) === null) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }
        $model->setScenario('agreementTime');

        // Проверка статуса
        if ($model->status != Visit::STATUS_TIME_EDITED) {
            Yii::$app->session->setFlash('error', Html::icon('alert') . ' Визит уже обработан');
            return $this->redirect([Yii::$app->user->getReturnUrl()]);
        }

        // Проверка доступа
        if (!Yii::$app->user->can(Rights::SHOW_VISIT_TIME_EDITED_FAMILY_NOTIFICATION, ['visit'=>$model]))
        {
            throw new ForbiddenHttpException($this->noAccessMessage);
        }

        if (Yii::$app->request->post('agreed') !== null) {
            $model->status = Visit::STATUS_AGREED;
            $model->save(false);
            return $this->redirect([Yii::$app->user->getReturnUrl()]);
        }

        if (Yii::$app->request->post('canceled') !== null) {
            $model->status = Visit::STATUS_CANCELED;
            $model->save(false);
            return $this->redirect([Yii::$app->user->getReturnUrl()]);
        }

        return $this->render('agreementTime', [
            'model' => $model,
        ]);
    }
}