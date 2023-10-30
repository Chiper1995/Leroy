<?php
namespace frontend\controllers;

use common\components\actions\DeleteAction;
use common\components\actions\ListAction;
use common\components\actions\UploadImageAction;
use common\components\actions\ViewAction;
use common\components\controllers\BaseController;
use common\components\controllers\IModelController;
use common\models\Journal;
use common\models\notifications\Notification;
use common\models\notifications\TaskOnAddNotification;
use common\models\Task;
use common\models\TaskPhoto;
use common\models\TaskUser;
use common\models\User;
use common\rbac\Rights;
use frontend\actions\TaskCreateAction;
use frontend\actions\TaskUpdateAction;
use frontend\actions\TaskJournalPDFAction;
use frontend\models\MyTasksSearch;
use frontend\models\TaskSearch;
use frontend\models\user\FamilySearch;
use frontend\models\journal\AllJournalSearch;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;


class TaskController extends BaseController implements IModelController
{

    public $layout = 'mainEmpty';

    public function getModelClass()
    {
        return Task::className();
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
                'class' => TaskCreateAction::className(),
                'access' => Rights::SHOW_TASKS,
                'returnUrl' => 'task/index',
            ],
            'update' => [
                'class' => TaskUpdateAction::className(),
                'returnUrl' => 'task/index',
                'access' => function($action, $model) {
                    /**@var Task $model*/
                    return Yii::$app->user->can(Rights::EDIT_TASKS, ['task'=>$model]);
                }
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'returnUrl' => 'task/index',
                'access' => function($action, $model) {
                    /**@var Task $model*/
                    return Yii::$app->user->can(Rights::EDIT_TASKS, ['task'=>$model]);
                }
            ],
            'upload-photo' => [
                'class' => UploadImageAction::className(),
                'modelClass' => TaskPhoto::className(),
            ],
            'view' => [
                'class' => ViewAction::className(),
                'access' => function($action, $model) {
                    /**@var Task $model*/
                    return
                        \Yii::$app->user->can(Rights::SHOW_TASKS, ['task' => $model])
                        or \Yii::$app->user->can(Rights::SHOW_TASKS_TO_ME, ['task' => $model]);
                },
            ],
            // Выгрузка дневник конкретной семьи в PDF
            'export-task-journal-to-pdf' => [
                'class' => TaskJournalPDFAction::className(),
                'modelClass' => Task::className(),
                'searchModelClass' => AllJournalSearch::className(),
                'view' => 'taskJournalPDF',
                // 'view' => '/journal/all-journals/allJournals',
                'access' => function($action, $model) {
                    /**@var Task $model*/
                    return
                        \Yii::$app->user->can(Rights::SHOW_TASKS, ['task' => $model])
                        or \Yii::$app->user->can(Rights::SHOW_TASKS_TO_ME, ['task' => $model]);
                },
                'dataProviderConfig' => [
                    'pagination' => false,
                ]
            ],
            // Выгрузка дневников в DOCX
            'export-task-journal-to-docx' => [
                'class' => TaskJournalPDFAction::className(),
                'modelClass' => Task::className(),
                'searchModelClass' => AllJournalSearch::className(),
                'view' => 'taskJournalDOCX',
                'access' => function($action, $model) {
                    return
                        \Yii::$app->user->can(Rights::SHOW_TASKS, ['task' => $model])
                        or \Yii::$app->user->can(Rights::SHOW_TASKS_TO_ME, ['task' => $model]);
                },
                'dataProviderConfig' => [
                    'pagination' => false,
                ]
            ],
            // Выборка заданий
            'index' => [
                'class' => ListAction::className(),
                'modelClass' => Task::className(),
                'searchModelClass' => TaskSearch::className(),
                'view' => 'list',
                'access' => Rights::SHOW_TASKS,
                'dataProviderConfig' => [
                    'pagination' => [
                        'pageSize' => 20,
                        'defaultPageSize' => 20,
                    ],
                    'sort' => [
                        'attributes' => [
                            'id',
                            'name',
                            'deadline',
                            'updated_at',
                        ],
                        'defaultOrder' => [
                            'updated_at' => SORT_DESC,
                            'name' => SORT_ASC,
                        ]
                    ]
                ]
            ],
            // Мои записи
            'my-tasks' => [
                'class' => ListAction::className(),
                'modelClass' => Task::className(),
                'searchModelClass' => MyTasksSearch::className(),
                'view' => 'myTasks',
                'access' => Rights::SHOW_TASKS_TO_ME,
                'dataProviderConfig' => [
                    'pagination' => [
                        'pageSize' => 10,
                        'defaultPageSize' => 10,
                    ],
                    'sort' => [
                        'attributes' => [
                            'updated_at'=>[
                                'asc' => ['{{%task}}.updated_at' => SORT_ASC],
                                'desc' => ['{{%task}}.updated_at' => SORT_DESC],
                            ],
                        ],
                        'defaultOrder' => [
                            'updated_at' => SORT_DESC,
                        ]
                    ]
                ]
            ],
        ];
    }

	public function actionGetAllFamiliesFromFamilySearchForTask()
	{
		if (!Yii::$app->user->can(Rights::SHOW_TASKS)) {
			throw new ForbiddenHttpException($this->noAccessMessage);
		}

		$searchModel = new FamilySearch();
		$dataProvider = $searchModel->search(User::className(), Yii::$app->request->queryParams, ['pagination' => ['pageSize' => 10000, 'defaultPageSize' => 10000]]);

		\Yii::$app->response->format = Response::FORMAT_JSON;
		return $dataProvider->keys;
	}

	public function actionRefuse($id)
    {
        /**@var Task $task*/
        if (($task = Task::findOne($id)) === null) {
            throw new NotFoundHttpException("Task not found");
        }

        if (!Yii::$app->user->can(Rights::SHOW_TASKS_TO_ME, ['task'=>$task]))
            throw new ForbiddenHttpException($this->noAccessMessage);

        /** @var TaskUser $family */
        if (($family = $task->getFamilies()->andWhere(['user_id'=>Yii::$app->user->id])->one()) === null) {
            throw new NotFoundHttpException("Family not found");
        }
        $journal = $family->journal;

        if ($journal != null) {
            if ($journal->status == Journal::STATUS_DRAFT) {
                $journal->delete();
            } else {
                throw new ForbiddenHttpException('Задание отправлено на проверку или уже выполнено. Отказ невозможен.');
            }
        }

        TaskUser::updateAll(['status' => TaskUser::STATUS_REFUSED], ['user_id'=>Yii::$app->user->id, 'task_id'=>$task->id]);

        $notificationIds = ArrayHelper::getColumn($family->linkedNotifications, 'id');

        Notification::setAllViewedByNotificationId($notificationIds);

        return $this->goBack(Url::toRoute(['my-tasks']));
    }
}
