<?php
namespace frontend\controllers;

use common\components\actions\ListAction;
use common\components\controllers\BaseController;
use common\models\User;
use common\rbac\Rights;
use frontend\actions\FamilyByShopReportExportToExcel;
use frontend\models\report\FamilyByShopReport;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class ReportController extends BaseController
{
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
            'families-by-shop' => [
                'class' => ListAction::className(),
                'modelClass' => User::className(),
                'searchModelClass' => FamilyByShopReport::className(),
                'view' => 'families-by-shop',
                'access' => Rights::SHOW_REPORTS,
                'dataProviderConfig' => [
                    'pagination' => [
                        'pageSize' => 12,
                        'defaultPageSize' => 12,
                    ],
                    'sort' => [
                        'attributes' => [
                            'family_name',
                        ],
                        'defaultOrder' => [
                            'family_name' => SORT_ASC,
                        ]
                    ]
                ]
            ],
            'families-by-shop-to-excel' => [
                'class' => FamilyByShopReportExportToExcel::className(),
                'modelClass' => User::className(),
                'searchModelClass' => FamilyByShopReport::className(),
                'access' => Rights::SHOW_REPORTS,
                'dataProviderConfig' => [
                    'pagination' => [
                        'pageSize' => 0,
                        'defaultPageSize' => 0,
                    ],
                    'sort' => [
                        'attributes' => [
                            'family_name',
                        ],
                        'defaultOrder' => [
                            'family_name' => SORT_ASC,
                        ]
                    ]
                ]
            ],
        ];
    }

    public function actionIndex()
    {
        // Проверка доступа
        if (!Yii::$app->user->can(Rights::SHOW_REPORTS, []))
        {
            throw new ForbiddenHttpException($this->noAccessMessage);
        }

        return $this->render('index', []);
    }
}