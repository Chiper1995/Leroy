<?php
namespace frontend\controllers;

use common\components\controllers\BaseController;
use common\rbac\Rights;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\components\controllers\IModelController;
use common\components\actions\CreateAction;
use common\components\actions\DeleteAction;
use common\components\actions\ListAction;
use common\components\actions\UpdateAction;

/**
 * ListDictController implements the CRUD actions for ListDict model.
 *
 */
abstract class ListDictController extends BaseController implements IModelController
{
    public abstract function getModelClass();

    public abstract function getModelSearchClass();

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@',],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'index' => [
                'class' => ListAction::className(),
                'access' => Rights::SHOW_DICTS,
                'searchModelClass' => $this->getModelSearchClass(),
                'dataProviderConfig' => [
                    'sort' => [
                        'attributes' => [
                            'id',
                            'name',
                            'updated_at',
                        ],
                        'defaultOrder' => [
                            'name' => SORT_ASC,
                            'updated_at' => SORT_DESC,
                        ]
                    ]
                ]
            ],
            'create' => [
                'class' => CreateAction::className(),
                'access' => Rights::SHOW_DICTS,
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'access' => Rights::SHOW_DICTS,
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'access' => Rights::SHOW_DICTS,
            ],
        ];
    }
}
