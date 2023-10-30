<?php

namespace frontend\controllers;

use common\components\actions\ViewAction;
use common\components\controllers\BaseController;
use common\models\Help;
use common\rbac\Rights;
use frontend\models\HelpSearch;
use ReflectionClass;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\components\controllers\IModelController;
use common\components\actions\CreateAction;
use common\components\actions\DeleteAction;
use common\components\actions\ListAction;
use common\components\actions\UpdateAction;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

/**
 * HelpController implements the CRUD actions for Help model.
 *
 */
class HelpController extends BaseController implements IModelController
{
    public function getModelClass()
    {
        return Help::className();
    }

    public function getModelSearchClass()
    {
        return HelpSearch::className();
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
                            'default',
                            'updated_at',
                        ],
                        'defaultOrder' => [
                            'title' => SORT_ASC,
                            'updated_at' => SORT_DESC,
                        ]
                    ]
                ]
            ],
            'create' => [
                'class' => CreateAction::className(),
                'access' => Rights::EDIT_HELP,
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'access' => Rights::EDIT_HELP,
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'access' => Rights::EDIT_HELP,
            ],
        ];
    }

    public function actionDownload($id = null)
    {
        $size = filesize('include/'.$id);

        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="presentation.pdf"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . $size);
    $file=file_get_contents('include/'.$id);
    echo $file;
    exit;
    }


    public function actionView($id = null)
    {
        /**@var Help $model */
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if ($id == null) {
            if (($model = $model::find()
                    ->andWhere(['default' => 1])
                    ->one()) === null) {
                throw new NotFoundHttpException("{$this->getModelClass()} not found");
            }
        } else {
            if (($model = $model::findOne($id)) === null) {
                throw new NotFoundHttpException("{$this->getModelClass()} not found");
            }
        }

        $roles = $model->getRoles();
        if (!empty($roles)) {
            if (!in_array(Yii::$app->user->getRole(), $model->getRoles()))
                throw new ForbiddenHttpException($this->noAccessMessage);
        }

        $model->setScenario('view');

        $helpPagesModels = $model->find()
            ->joinWith('helpRoles r')
            ->andWhere([
                'OR',
                'r.role IS NULL',
                ['IN', 'r.role', Yii::$app->user->getRole()]
            ])
            ->orderBy('default DESC, title ASC')->all();

        $helpPages = [];
        foreach ($helpPagesModels as $helpPagesModel) {
            $helpPages[] = [
                'label' => $helpPagesModel->title,
                'url' => ['help/view', 'id' => $helpPagesModel->id],
                'active' => $helpPagesModel->id == $model->id,
            ];
        }

        return $this->render('view', [
            'model' => $model,
            'helpPages' => $helpPages,
        ]);
    }

}
