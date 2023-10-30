<?php
namespace frontend\controllers;

use common\components\actions\DeleteAction;
use common\components\actions\ListAction;
use common\components\actions\UploadImageAction;
use common\components\controllers\BaseController;
use common\components\controllers\IModelController;
use common\events\AppEvents;
use common\models\Journal;
use common\models\JournalCheckPhoto;
use common\models\JournalPhoto;
use common\models\User;
use common\rbac\Rights;
use frontend\actions\FamilyJournalAction;
use frontend\actions\JournalAutoSaveAction;
use frontend\actions\JournalCreateAction;
use frontend\actions\JournalCreateFromTaskAction;
use frontend\actions\JournalUpdateAction;
use frontend\actions\JournalUploadPhotoAction;
use frontend\actions\JournalViewAction;
use frontend\actions\JournalViewPdfAction;
use frontend\actions\JournalViewDocAction;
use frontend\models\journal\AllJournalSearch;
use frontend\models\journal\AllJournalSmartSearch;
use frontend\models\journal\FamilyJournalPublishedSearch;
use frontend\models\journal\FamilyJournalSearch;
use frontend\models\journal\MyJournalSearch;
use frontend\models\journal\MySubscriptionSearch;
use frontend\models\journal\UserAllJournalSearch;
use frontend\models\journal\UserAllJournalSmartSearch;
use ReflectionClass;
use Yii;
use yii\base\Event;
use yii\bootstrap\Html;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\sphinx\MatchExpression;
use yii\sphinx\Query;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class JournalController
 * @package frontend\controllers
 * @property $modelClass
 * @property $modelSearchClass
 */
class JournalController extends BaseController implements IModelController
{

    public $layout = 'mainEmpty';

    public function getModelClass()
    {
        return Journal::className();
    }

    public function getModelSearchClass()
    {
        return null;//JournalSearch::className();
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
                    'like-it' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'create' => [
                'class' => JournalCreateAction::className(),
                'returnUrl' => 'journal/my-journal',
                'access' => Rights::SHOW_MY_JOURNAL_RECORDS,
            ],
            'create-from-task' => [
                'class' => JournalCreateFromTaskAction::className(),
                'returnUrl' => 'task/my-tasks',
                'access' => Rights::SHOW_TASKS_TO_ME, // Так как можно создать только из задания
            ],
            'update' => [
                'class' => JournalUpdateAction::className(),
                'returnUrl' => 'journal/my-journal',
                'access' => function($action, $model) {
                    /**@var Journal $model*/
                    return Yii::$app->user->can(Rights::EDIT_JOURNAL, ['journal'=>$model]);
                },
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'returnUrl' => 'journal/my-journal',
                'access' => function($action, $model) {
                    /**@var Journal $model*/
                    return Yii::$app->user->can(Rights::EDIT_JOURNAL, ['journal'=>$model]);
                },
            ],
            'upload-photo' => [
                'class' => JournalUploadPhotoAction::className(),
                'modelClass' => JournalPhoto::className(),
            ],
            'upload-check-photo' => [
                'class' => UploadImageAction::className(),
                'modelClass' => JournalCheckPhoto::className(),
            ],
            'view' => [
                'class' => JournalViewAction::className(),
                'access' => function($action, $model) {
                    /**@var Journal $model*/
                    if (Yii::$app->user->can(Rights::SHOW_MY_JOURNAL_RECORDS)) {
                        return
                            ($model->user_id == Yii::$app->user->id)
                            || (($model->status == Journal::STATUS_PUBLISHED)and($model->visibility == Journal::VISIBILITY_FOR_ALL));
                    }
                    else {
                        return
                            (Yii::$app->user->can(Rights::SHOW_JOURNALS, ['user'=>$model->user]))
                            || (($model->status == Journal::STATUS_PUBLISHED)and($model->visibility == Journal::VISIBILITY_FOR_ALL));
                    }
                },
            ],
            'viewpdf' => [
                'class' => JournalViewPdfAction::className(),
                'access' => function($action, $model) {
                    /**@var Journal $model*/
                    if (Yii::$app->user->can(Rights::SHOW_MY_JOURNAL_RECORDS)) {
                        return
                            ($model->user_id == Yii::$app->user->id)
                            || (($model->status == Journal::STATUS_PUBLISHED) && ($model->visibility == Journal::VISIBILITY_FOR_ALL));
                    }
                    else {
                        return
                            (Yii::$app->user->can(Rights::SHOW_JOURNALS, ['user'=>$model->user]))
                            || (($model->status == Journal::STATUS_PUBLISHED) && ($model->visibility == Journal::VISIBILITY_FOR_ALL));
                    }
                },
            ],
            'viewdoc' => [
                'class' => JournalViewDocAction::className(),
                'access' => function($action, $model) {
                    /**@var Journal $model*/
                    if (Yii::$app->user->can(Rights::SHOW_MY_JOURNAL_RECORDS)) {
                        return
                            ($model->user_id == Yii::$app->user->id)
                            || (($model->status == Journal::STATUS_PUBLISHED) && ($model->visibility == Journal::VISIBILITY_FOR_ALL));
                    }
                    else {
                        return
                            (Yii::$app->user->can(Rights::SHOW_JOURNALS, ['user'=>$model->user]))
                            || (($model->status == Journal::STATUS_PUBLISHED) && ($model->visibility == Journal::VISIBILITY_FOR_ALL));
                    }
                },
            ],
            // Мои записи
            'my-journal' => [
                'class' => ListAction::className(),
                'modelClass' => Journal::className(),
                'searchModelClass' => MyJournalSearch::className(),
                'view' => 'myJournal',
                'access' => Rights::SHOW_MY_JOURNAL_RECORDS,
                'dataProviderConfig' => [
                    'pagination' => [
                        'pageSize' => 12,
                        'defaultPageSize' => 12,
                    ],
                    'sort' => [
                        'attributes' => [
                            'created_at',
                        ],
                        'defaultOrder' => [
                            'created_at' => SORT_DESC,
                        ]
                    ]
                ]
            ],

            // Лента
            'index' => [
                'class' => ListAction::className(),
                'modelClass' => Journal::className(),
                'searchModelClass' => UserAllJournalSearch::className(),
                'view' => 'userAllJournals',
                'dataProviderConfig' => [
                    'pagination' => [
                        'pageSize' => 12,
                        'defaultPageSize' => 12,
                    ],
                    'sort' => [
                        'attributes' => [
                            'updated_at',
                        ],
                        'defaultOrder' => [
                            'updated_at' => SORT_DESC,
                        ]
                    ]
                ]
            ],

            // Записи всех семей
            'all-journals' => [
                'class' => ListAction::className(),
                'modelClass' => Journal::className(),
                'searchModelClass' => AllJournalSearch::className(),
                'view' => 'all-journals/allJournals',
                'access' => Rights::SHOW_JOURNALS,
                'dataProviderConfig' => [
                    'pagination' => [
                        'pageSize' => 12,
                        'defaultPageSize' => 12,
                    ],
                    'sort' => [
                        'attributes' => [
                            'updated_at',
                            'return_reason'
                        ],
                        'defaultOrder' => [
                            'updated_at' => SORT_DESC,
                        ]
                    ]
                ]
            ],

            // Дневник конкретной семьи
            'family-journal' => [
                'class' => FamilyJournalAction::className(),
                'modelClass' => Journal::className(),
                'searchModelClass' => FamilyJournalSearch::className(),
                'view' => 'family-journal/familyJournal',
                'access' => function($action, $model) {
                    return Yii::$app->user->can(Rights::SHOW_FAMILY_JOURNAL, ['user'=>$model]);
                },
                'dataProviderConfig' => [
                    'pagination' => [
                        'pageSize' => 12,
                        'defaultPageSize' => 12,
                    ],
                    'sort' => [
                        'attributes' => [
                            'updated_at',
                        ],
                        'defaultOrder' => [
                            'updated_at' => SORT_DESC,
                        ]
                    ]
                ]
            ],

            // Выгрузка дневник конкретной семьи в PDF
            'export-family-journal-to-pdf' => [
                'class' => FamilyJournalAction::className(),
                'modelClass' => Journal::className(),
                'searchModelClass' => FamilyJournalPublishedSearch::className(),
                'view' => 'family-journal/familyJournalPDF',
				'access' => function($action, $model) {
					return Yii::$app->user->can(Rights::SHOW_FAMILY_JOURNAL, ['user'=>$model]);
				},
                'dataProviderConfig' => [
                    'pagination' => false,
                    'sort' => [
                        'attributes' => [
                            'updated_at',
                        ],
                        'defaultOrder' => [
                            'updated_at' => SORT_ASC,
                        ]
                    ]
                ]
            ],

            // Мои подписки
            'my-subscription' => [
                'class' => ListAction::className(),
                'modelClass' => Journal::className(),
                'searchModelClass' => MySubscriptionSearch::className(),
                'view' => 'mySubscription',
                'dataProviderConfig' => [
                    'pagination' => [
                        'pageSize' => 12,
                        'defaultPageSize' => 12,
                    ],
                    'sort' => [
                        'attributes' => [
                            'updated_at',
                        ],
                        'defaultOrder' => [
                            'updated_at' => SORT_DESC,
                        ]
                    ]
                ]
            ],

            'auto-save' => [
                'class' => JournalAutoSaveAction::className(),
                'returnUrl' => 'journal/my-journal',
                'access' => function($action, $model) {
                    /**@var Journal $model*/
                    return Yii::$app->user->can(Rights::EDIT_MY_JOURNAL, ['journal'=>$model]);
                },
            ],

			// Smart-поиск
			'smart-search' => [
				'class' => ListAction::className(),
				'modelClass' => null,
				'modelScenario' => null,
				'searchModelClass' => Yii::$app->user->can(Rights::SHOW_JOURNAL_SMART_SEARCH)
					? AllJournalSmartSearch::className()
					: UserAllJournalSmartSearch::className(),
				'view' => 'smart-search/results',
				'dataProviderConfig' => [
					'pagination' => [
						'pageSize' => 10,
						'defaultPageSize' => 10,
					],
				]
			],

			'smart-search-export-to-pdf' => [
                'class' => ListAction::className(),
                'modelClass' => null,
				'modelScenario' => null,
                'searchModelClass' => AllJournalSmartSearch::className(),
                'view' => 'smart-search/resultsPDF',
                'access' => Rights::SHOW_JOURNAL_SMART_SEARCH,
                'dataProviderConfig' => [
					'pagination' => [
						'pageSize' => 10000,
						'defaultPageSize' => 10000,
					],
                ]
            ],

			'smart-search-export-to-docx' => [
				'class' => ListAction::className(),
				'modelClass' => null,
				'modelScenario' => null,
				'searchModelClass' => AllJournalSmartSearch::className(),
				'view' => 'smart-search/resultsDOCX',
				'access' => Rights::SHOW_JOURNAL_SMART_SEARCH,
				'dataProviderConfig' => [
					'pagination' => [
						'pageSize' => 10000,
						'defaultPageSize' => 10000,
					],
				]
			],
        ];
    }

	/**
	 * Проверка записи дневника
	 * @param $id
	 * @return string|\yii\web\Response
	 * @throws ForbiddenHttpException
	 * @throws NotFoundHttpException
	 * @throws \ReflectionException
	 */
    public function actionCheck($id)
    {
        /**@var Journal $model*/
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if (($model = $model::findOne($id)) === null) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }

        // Проверка статуса
        if ($model->status != Journal::STATUS_ON_CHECK) {
            Yii::$app->session->setFlash('error', Html::icon('alert') . ' Эту запись уже проверили');
            return $this->redirect([Yii::$app->user->getReturnUrl()]);
        }

        // Проверка доступа
        if (
            (!Yii::$app->user->can(Rights::SHOW_JOURNAL_ON_CHECK_NOTIFICATION, ['journal'=>$model]))
            && (!Yii::$app->user->can(Rights::SHOW_JOURNAL_BY_TASK_ON_CHECK_NOTIFICATION, ['journal'=>$model]))
        )
        {
            throw new ForbiddenHttpException($this->noAccessMessage);
        }

        if (Yii::$app->request->post('checked') !== null) {
            $model->setScenario(Journal::SCENARIO_CHECK);
            $model->saveViewed($model->user_id);
            if ($model->load(Yii::$app->request->post())) {
                $model->publish();
                return $this->redirect([Yii::$app->user->getReturnUrl()]);
            }
        }

        if (Yii::$app->request->post('returnToEdit') !== null) {
            $model->setScenario(Journal::SCENARIO_RETURN_TO_EDIT);
            if ($model->load(Yii::$app->request->post())) {
                if ($model->return_reason == '')
                    $model->return_reason = Journal::REASON_DEFAULT;
                $model->status = Journal::STATUS_DRAFT;
                if ($model->save(false))
                    JournalPhoto::updateAll(['status' => JournalPhoto::STATUS_DRAFT], ['journal_id' => $model->id]);
                return $this->redirect([Yii::$app->user->getReturnUrl()]);
            }
        }

        $model->saveViewed(Yii::$app->user->id);

        return $this->render('check', [
            'model' => $model,
        ]);
    }

	/**
	 * Проверка новых фотографий в опубликованной записи дневника
	 * @param $id
	 * @return string|\yii\web\Response
	 * @throws ForbiddenHttpException
	 * @throws NotFoundHttpException
	 * @throws \ReflectionException
	 */
    public function actionCheckPhoto($id)
    {
        /**@var Journal $model*/
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if (($model = $model::findOne($id)) === null) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }

        // Проверка статуса
        if ($model->status != Journal::STATUS_PUBLISHED || count($model->getOnCheckPhotos()) == 0) {
            Yii::$app->session->setFlash('error', Html::icon('alert') . ' Нет новых фотографий на проверку в этой записи');
            return $this->redirect([Yii::$app->user->getReturnUrl()]);
        }

        // Проверка доступа
        if (
            (!Yii::$app->user->can(Rights::SHOW_JOURNAL_ON_CHECK_NOTIFICATION, ['journal'=>$model]))
            && (!Yii::$app->user->can(Rights::SHOW_JOURNAL_BY_TASK_ON_CHECK_NOTIFICATION, ['journal'=>$model]))
        )
        {
            throw new ForbiddenHttpException($this->noAccessMessage);
        }

        if (Yii::$app->request->post('checked') !== null) {
            $model->return_photo_reason = '';
            if ($model->save(false)) {
                JournalPhoto::updateAll(
                    ['status' => JournalPhoto::STATUS_PUBLISHED],
                    ['journal_id' => $model->id, 'status' => JournalPhoto::STATUS_ON_CHECK]
                );
                Yii::$app->trigger(AppEvents::EVENT_JOURNAL_PHOTO_ON_PUBLISHED, new Event(['sender' => $model]));
            }
            return $this->redirect([Yii::$app->user->getReturnUrl()]);
        }

        if (Yii::$app->request->post('returnToEdit') !== null) {
            $model->setScenario(Journal::SCENARIO_RETURN_PHOTO_TO_EDIT);
            if ($model->load(Yii::$app->request->post())) {
                if ($model->return_photo_reason == '')
                    $model->return_photo_reason = 'не указана';
                if ($model->save(false)) {
                    JournalPhoto::updateAll(
                        ['status' => JournalPhoto::STATUS_DRAFT],
                        ['journal_id' => $model->id, 'status' => JournalPhoto::STATUS_ON_CHECK]
                    );
                    Yii::$app->trigger(AppEvents::EVENT_JOURNAL_PHOTO_ON_RETURN_TO_EDIT, new Event(['sender' => $model]));
                }

                return $this->redirect([Yii::$app->user->getReturnUrl()]);
            }
        }

        return $this->render('checkPhotoInPublishedJournal', [
            'model' => $model,
        ]);
    }

    public function actionLikeIt($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        /**@var Journal $model*/
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if (($model = $model::findOne($id)) === null) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }

        // Проверка статуса
        if ($model->status != Journal::STATUS_PUBLISHED) {
            return ['status' => 'error', 'message' => 'Запись не опубликована'];
        }

        /** @var User $user */
        $user = Yii::$app->user->identity;
        if ($model->currentUserLikeIt())
            $model->unlink('likeUsers', $user, true);
        else
            $model->link('likeUsers', $user);

        $model->refresh();

        return [
            'status' => 'success',
            'currentUserLikeIt' => $model->currentUserLikeIt(),
            'likeCount' => $model->getLikeUsersCount(),
        ];
    }

    /*public function actionTest($keyword)
	{
		$q = new Query();
		$suggestionsData = $q->createCommand()
			->setSql('CALL SUGGEST(:keyword, \'journals_keywords_translit\', 20 as limit, 50 as reject, 2 as delta_len, 2 as max_edits)')
			->bindParam(':keyword', $keyword)
			->queryAll();

		$suggestions = [];
		$suggestions[] = $keyword;
		foreach ($suggestionsData as $item) {
			if ($item['distance'] > 0) {
				$suggestions[] = $item['suggest'];
			}
		}

		$keywordsData = $q->createCommand()
			->setSql('CALL KEYWORDS(:keywords, \'journals\', 0 as stats, 1 as fold_lemmas)')
			->bindValue(':keywords', implode(' ', $suggestions))
			->queryAll();

		var_dump($suggestions);
		var_dump($keywordsData);
	}*/
}
