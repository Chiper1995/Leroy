<?php

namespace frontend\controllers;

use common\components\actions\ViewAction;
use common\components\controllers\BaseController;
use common\models\Help;
use common\models\Presentation;
use common\rbac\Rights;
use frontend\models\PresentationSearch;
use ReflectionClass;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\components\controllers\IModelController;
use common\components\actions\CreatePdfAction;
use common\components\actions\DeleteAction;
use common\components\actions\ListAction;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use common\components\actions\UploadPdfAction;

/**
 * PresentationController implements the CRUD actions for Help model.
 *
 */
class PresentationController extends BaseController implements IModelController
{
    public function getModelClass()
    {
        return Presentation::className();
    }

    public function getModelSearchClass()
    {
        return PresentationSearch::className();
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
                'access' => Rights::EDIT_HELP,
                'searchModelClass' => $this->getModelSearchClass(),
                'dataProviderConfig' => [
                    'sort' => [
                        'attributes' => [
                            'id',
                            'title',
                            'content',
                            'help_id',
                            'updated_at',
                            'file'
                        ],
                        'defaultOrder' => [
                            'title' => SORT_ASC,
                            'updated_at' => SORT_DESC,
                        ]
                    ],
                ],
            ],
            'create' => [
                'class' => CreatePdfAction::className(),
                'access' => Rights::EDIT_HELP,
            ],
            'update' => [
                'class' => UploadPdfAction::className(),
                'access' => Rights::EDIT_HELP,
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'access' => Rights::EDIT_HELP,
            ],
        ];
    }

    public function actionView($id = null)
    {
        /**@var Help $model */
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if ($id == null) {
                throw new NotFoundHttpException("{$this->getModelClass()} not found");
        } else {
            if (($model = $model::findOne($id)) === null) {
                throw new NotFoundHttpException("{$this->getModelClass()} not found");
            }
        }

        $model->setScenario('view');

        $helpPagesModels = $model->find()
            ->orderBy('title ASC')->all();

        $helpPages = [];
        foreach ($helpPagesModels as $helpPagesModel) {
            $helpPages[] = [
                'label' => $helpPagesModel->title,
                'url' => ['/help/presentation/view', 'id' => $helpPagesModel->id],
                'options' => [
                    'data-pdf' => md5($helpPagesModel->file),
                    'data-name' => str_replace(".pdf", "", $helpPagesModel->file)
                ],
                'active' => $helpPagesModel->default,
            ];
        }

        return $this->render('view', [
            'model' => $model,
            'helpPages' => $helpPages,
        ]);
    }

}
