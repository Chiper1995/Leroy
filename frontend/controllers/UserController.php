<?php

namespace frontend\controllers;

use common\components\actions\UploadImageAction;
use common\components\actions\ViewAction;
use common\components\controllers\BaseController;
use common\components\helpers\SortHelper;
use common\components\helpers\MapApiHelper;
use common\models\City;
use common\models\Earnings;
use common\models\Gift;
use common\models\Journal;
use common\models\notifications\Notification;
use common\models\Spending;
use common\models\staticLists\Bool;
use common\models\User;
use common\rbac\Rights;
use frontend\models\user\ProfileFamilyForm;
use frontend\models\user\FamiliesForFamilySearch;
use frontend\models\user\FamilyGiveGiftForm;
use frontend\models\user\FamilyIncreasePointsForm;
use frontend\models\user\FamilyReducePointsForm;
use frontend\models\user\FamilySearch;
use frontend\models\user\FamilySetCuratorForm;
use frontend\models\user\UserSearch;
use ReflectionClass;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\components\controllers\IModelController;
use frontend\actions\UserDeleteAction;
use frontend\actions\LocationsViewAction;
use common\components\actions\ListAction;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseController implements IModelController
{
    public function getModelClass()
    {
        return User::className();
    }

    public function getModelSearchClass()
    {
        return UserSearch::className();
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
                    'set-end-repair-status' => ['post'],
                    'set-prof-status' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        $actions = [
            'index' => [
                'class' => ListAction::className(),
                'access' => Rights::SHOW_USERS,
                'searchModelClass' => $this->getModelSearchClass(),
                'view' => 'users/list',
                'dataProviderConfig' => [
                    'sort' => [
                        'attributes' => [
                            'id',
                            'fio',
                            'username',
                            'updated_at',
                        ],
                        'defaultOrder' => [
                            'id' => SORT_DESC,
                            'updated_at' => SORT_DESC,
                        ]
                    ]
                ]
            ],
            'delete' => [
                'class' => UserDeleteAction::className(),
                'access' => function ($action, $model) {
                    if ($model->role == User::ROLE_FAMILY) {
                        return Yii::$app->user->can(Rights::SHOW_FAMILIES);
                    } else {
                        return Yii::$app->user->can(Rights::SHOW_USERS);
                    }
                },
            ],
            'view' => [
                'class' => ViewAction::className(),
                'view' => 'users/view',
                'access' => function ($action, $model) {
                    if (in_array($model->role, [
                        User::ROLE_ADMINISTRATOR,
                        User::ROLE_MARKETING,
                        User::ROLE_ALL_LM,
                        User::ROLE_PURCHASE,
                        User::ROLE_SHOP,
                        User::ROLE_SHOP_MODERATOR,
                        User::ROLE_DEVELOPER,
                        User::ROLE_MARKETING_PLUS
                    ])
                    ) { // ANDR Убрать ROLE_DEVELOPER
                        return Yii::$app->user->can(Rights::SHOW_ADMINISTRATOR_INFO);
                    } else {
                        throw new NotFoundHttpException("User not found");
                    }
                },
            ],
            'family-view' => [
                'class' => ViewAction::className(),
                'view' => 'familyView',
                'access' => function ($action, $model) {
                    if ($model->role == User::ROLE_FAMILY) {
                        return Yii::$app->user->can(Rights::SHOW_FAMILIES);
                    } else {
                        throw new NotFoundHttpException("Family not found");
                    }
                },
            ],
            // Загрука фото
            'upload-photo' => [
                'class' => UploadImageAction::className(),
                'modelClass' => User::className(),
            ],
            'families' => [
                'class' => ListAction::className(),
                'access' => Rights::SHOW_FAMILIES,
                'searchModelClass' => FamilySearch::className(),
                'view' => 'familyList',
                'dataProviderConfig' => [
                    'sort' => [
                        'attributes' => [
                            'id',
                            'status',
                            'fio',
                            'username',
                            'email',
                            'phone',
                            'points',
                            'curator.fio',
                            'updated_at',
                            'is_prof' => SortHelper::getAttributeConfigByStaticList(Bool::className(), 't.is_prof'),
                        ],
                        'defaultOrder' => [
                            'fio' => SORT_ASC,
                            'updated_at' => SORT_DESC,
                        ]
                    ]
                ]
            ],
            'families-search' => [
                'class' => ListAction::className(),
                'access' => Rights::SHOW_FAMILIES_FOR_FAMILIES,
                'searchModelClass' => FamiliesForFamilySearch::className(),
                'view' => 'families/familyListForFamilies',
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
            ]
        ];

        return $actions;
    }

    /**
     * Создание пользователей (не семьи)
     * @return array|string|Response
     * @throws ForbiddenHttpException
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        if (Yii::$app->request->post('cancel') !== null) {
            return $this->redirect(['index']);
        }

        // Проверка доступа
        if (!Yii::$app->user->can(Rights::SHOW_USERS))
            throw new ForbiddenHttpException($this->noAccessMessage);

        /**@var User $model */
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        $model->setScenario('create');

        // Если ajax
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            // В этом экшене нельзя создавать семьи
            if ($model->role == User::ROLE_FAMILY)
                $model->role = null;

            // Города
            $_cities = array();
            if (in_array($model->role, User::getRoleNeedSetCity())) {
                $citiesData = ArrayHelper::getValue(Yii::$app->request->post($model->formName()), 'cities', null);
                if (($citiesData != null) and (is_array($citiesData))) {
                    foreach ($citiesData as $cityId) {
                        if (($city = City::findOne($cityId)) != null) {
                            $_cities[] = $city;
                        }
                    }
                }
            }
            $model->populateRelation('cities', $_cities);

            if ($model->validate() && $model->validate(['cities'])) {
                $model->password = $model->set_password;
                $model->status = User::STATUS_ACTIVE;
                $model->generateAuthKey();

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model->save()) {
                        // Сохраняем города
                        foreach ($model->cities as $city) {
                            $model->link('cities', $city);
                        }

                        $transaction->commit();

                        // Выходим в выборку
                        return $this->redirect(['index']);
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Возникла ошибка при сохранении');
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }
        }

        return $this->render('users/create', [
            'model' => $model,
        ]);
    }

    /**
     * Редактирование пользователей (не семьи)
     * @param integer $id
     * @return array|string|Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->request->post('cancel') !== null) {
            return $this->redirect(['index']);
        }

        /**@var User $model */
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if (($model = $model::findOne($id)) === null) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }
        $model->setScenario('update');

        // Проверка доступа
        // В этом экшене нельзя редактировать семьи
        if ((!Yii::$app->user->can(Rights::SHOW_USERS)) or ($model->role == User::ROLE_FAMILY))
            throw new ForbiddenHttpException($this->noAccessMessage);

        // Если ajax
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            // В этом экшене нельзя редактировать семьи
            if ($model->role == User::ROLE_FAMILY)
                $model->role = null;

            // Города
            $_cities = array();
            if (in_array($model->role, User::getRoleNeedSetCity())) {
                $citiesData = ArrayHelper::getValue(Yii::$app->request->post($model->formName()), 'cities', null);
                if (($citiesData != null) and (is_array($citiesData))) {
                    foreach ($citiesData as $cityId) {
                        if (($city = City::findOne($cityId)) != null) {
                            $_cities[] = $city;
                        }
                    }
                }
            }
            $model->populateRelation('cities', $_cities);

            if ($model->validate() && $model->validate(['cities'])) {
                if (strlen($model->set_password) > 0)
                    $model->password = $model->set_password;
                $model->generateAuthKey();

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model->save()) {
                        // Сохраняем города
                        /**@var User $prevModel */
                        $prevModel = (new ReflectionClass($this->getModelClass()))->newInstance();
                        $prevModel = $prevModel::findOne($model->id);
                        $prevModel->unlinkAll('cities', true);

                        foreach ($model->cities as $city) {
                            $model->link('cities', $city);
                        }

                        $transaction->commit();

                        // Выходим в выборку
                        return $this->redirect(['index']);
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Возникла ошибка при сохранении');
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }
        }

        return $this->render('users/update', [
            'model' => $model,
        ]);
    }

    public function actionProfile()
    {
        $request = Yii::$app->request;
        /**@var User $model */
        $model = Yii::$app->user->identity;
        if ($model === null) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }
        $model->setScenario($model->role == User::ROLE_FAMILY ? 'familyProfileUpdate' : 'userProfileUpdate');

        $formProfile = new ProfileFamilyForm();
        $formProfile->fillFromUser($model);
        if ($model->role != User::ROLE_FAMILY) {
            if (strpos($model->fio, User::FIO_SUFFIX) !== false) {
                $session = Yii::$app->session;
                if (!$session->isActive) {
                    $session->open();
                }
                $session->set('add_suffix', 'Y');
                $model->fio = trim(str_replace(User::FIO_SUFFIX, "", $model->fio));
            }
        }
        // Если ajax
        if ($request->isAjax && $model->load($request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load($request->post())) {
            if ($model->validate()) {
                if (strlen($model->set_password) > 0)
                    $model->password = $model->set_password;
                if ($model->role != User::ROLE_FAMILY) {
                    $session = Yii::$app->session;
                    if ($session->isActive && $session->has('add_suffix') && $session->get('add_suffix') == "Y") {
                        $model->fio .= ' ' . User::FIO_SUFFIX;
                        $session->remove('add_suffix');
                    }
                }
                if ($model->save() && $formProfile->saveFromUser($model, $request->post())) {
                    return $this->redirect([Yii::$app->user->getReturnUrl()]);
                } else {
                    Yii::$app->session->setFlash('error', 'Возникла ошибка при сохранении');
                }
            }
        }

        return $this->render($model->role == User::ROLE_FAMILY ? 'familyProfile' : 'users/userProfile', [
            'model' => $model,
            'formProfile' => $formProfile,
        ]);
    }

    /**
     * Активация пользователя семьи
     * @param integer $id
     * @return string|Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionActivate($id)
    {
        /**@var User $model */
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if (($model = $model::findOne($id)) === null) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }
        $model->setScenario('view');

        // Проверка статуса
        if ($model->status != User::STATUS_NEW) {
            Yii::$app->session->setFlash('error', Html::icon('alert') . ' Семья уже обработана');
            return $this->redirect([Yii::$app->user->getReturnUrl()]);
        }

        // Проверка доступа
        if (!Yii::$app->user->can(Rights::SHOW_NEW_USER_REGISTER_NOTIFICATION, ['user' => $model]))
            throw new ForbiddenHttpException($this->noAccessMessage);

        // Активируем
        if (Yii::$app->request->post('activate') !== null) {
            $model->activate();
            return $this->redirect([Yii::$app->user->getReturnUrl()]);
        }

        // Удаляем
        if (Yii::$app->request->post('delete') !== null) {
            $model->delete();
            return $this->redirect([Yii::$app->user->getReturnUrl()]);
        }

        return $this->render('activate', [
            'model' => $model,
        ]);
    }

    public function actionReducePoints()
    {
        // Проверка доступа
        if (!Yii::$app->user->can(Rights::SPEND_POINTS, []))
            throw new ForbiddenHttpException($this->noAccessMessage);

        $model = new FamilyReducePointsForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                /**@var User $family */
                if (($family = User::findOne($model->family_id)) === null) {
                    throw new NotFoundHttpException("Family not found");
                }

                $db = Yii::$app->db;

                $transaction = $db->beginTransaction();
                try {
                    $spending = new Spending();
                    $spending->user_id = Yii::$app->user->id;
                    $spending->family_id = $family->id;
                    $spending->points = $model->points;
                    $spending->description = $model->description;
                    $spending->save(false);

                    $family = User::find()->withPoints()->where(['id' => $model->family_id])->one();

                    if ($family->points < 0) {
                        $model->addError('points', 'У этой семьи нет столько баллов, попробуй списать меньше');
                        $transaction->rollBack();
                    } else {
                        $transaction->commit();
                        $model->saved = true;
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }
        }

        return $this->render('__modal_reduce_points', ['model' => $model]);
    }

    public function actionIncreasePoints()
    {
        // Проверка доступа
        if (!Yii::$app->user->can(Rights::EARN_POINTS, []))
            throw new ForbiddenHttpException($this->noAccessMessage);

        $model = new FamilyIncreasePointsForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                /**@var User $family */
                if (($family = User::findOne($model->family_id)) === null) {
                    throw new NotFoundHttpException("Family not found");
                }

                $db = Yii::$app->db;

                $transaction = $db->beginTransaction();
                try {
                    $earnings = new Earnings();
                    $earnings->user_id = Yii::$app->user->id;
                    $earnings->family_id = $family->id;
                    $earnings->points = $model->points;
                    $earnings->description = $model->description;
                    $earnings->save(false);

                    $transaction->commit();
                    $model->saved = true;
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }
        }

        return $this->render('__modal_increase_points', ['model' => $model]);
    }

    public function actionSetCurator()
    {
        // Проверка доступа
        if (!Yii::$app->user->can(Rights::FAMILY_SET_CURATOR, []))
            throw new ForbiddenHttpException($this->noAccessMessage);

        $model = new FamilySetCuratorForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                /**@var User $family */
                if (($family = User::findOne($model->family_id)) === null) {
                    throw new NotFoundHttpException("Family not found");
                }

                $family->curator_id = $model->curator_id;
                if ($family->save(true, ['curator_id'])) {
                    $model->saved = true;
                }
            }
        }

        return $this->render('__modal_set_curator', ['model' => $model]);
    }

    public function actionResetPassword($id)
    {
        /**@var User $model */
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if ((($model = $model::findOne($id)) === null) || ($model->role != User::ROLE_FAMILY)) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }
        $model->setScenario('resetPassword');

        // Проверка доступа
        if (!Yii::$app->user->can(Rights::FAMILY_RESET_PASSWORD, []))
            throw new ForbiddenHttpException($this->noAccessMessage);

        $newPassword = strtolower(Yii::$app->security->generateRandomString(6));

        $model->password = $newPassword;
        if ($model->save(false))
            Yii::$app->session->setFlash('info', Html::icon('alert') . ' Пароль пользователя "' . $model->username . '" изменен на "' . $newPassword . '"');
        else
            Yii::$app->session->setFlash('error', Html::icon('alert') . ' Возникли ошибки при изменениии пароля');

        return $this->redirect([Yii::$app->user->getReturnUrl()]);
    }

    public function actionLoginAs($id)
    {
        /**@var User $model */
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if (($model = $model::findOne($id)) === null) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }
        $model->setScenario('view');

        // Проверка доступа
        if (!Yii::$app->user->can(Rights::USER_LOGIN_AS, []))
            throw new ForbiddenHttpException($this->noAccessMessage);

        $parentLoggedUserId = Yii::$app->user->id;
        Yii::$app->user->login($model, 0);
        Yii::$app->session->set('PARENT_LOGGED_USER_ID', $parentLoggedUserId);

        return $this->redirect([Yii::$app->homeUrl]);
    }

    public function actionLoginAsParentLoggedUser()
    {
        // Проверка доступа
        if (($parentLoggedUserId = Yii::$app->session->get('PARENT_LOGGED_USER_ID')) === null)
            throw new ForbiddenHttpException($this->noAccessMessage);

        /**@var User $model */
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if (($model = $model::findOne($parentLoggedUserId)) === null) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }
        $model->setScenario('view');

        Yii::$app->user->login($model, 0);
        Yii::$app->session->remove('PARENT_LOGGED_USER_ID');

        return $this->redirect([Yii::$app->homeUrl]);
    }

    public function actionResetNotifications($id)
    {
        /**@var User $model */
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if ((($model = $model::findOne($id)) === null) || ($model->role == User::ROLE_FAMILY)) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }
        $model->setScenario('resetNotifications');

        // Проверка доступа
        if (!Yii::$app->user->can(Rights::USER_RESET_NOTIFICATIONS, []))
            throw new ForbiddenHttpException($this->noAccessMessage);

        Notification::setAllViewedByUserId($model->id, true);

        Yii::$app->session->setFlash('info', Html::icon('alert') . ' Для пользователя "' . $model->username . '" сброшены все уведомления');

        return $this->redirect([Yii::$app->user->getReturnUrl()]);
    }

    /**
     * Установить статус "Ремонт окончен"
     * @param $id
     * @return Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionSetEndRepairStatus($id)
    {
        /**@var User $model */
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if ((($model = $model::findOne($id)) === null) || ($model->role != User::ROLE_FAMILY)) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }
        $model->setScenario('setEndRepairStatus');

        // Проверка доступа
        if (!Yii::$app->user->can(Rights::FAMILY_SET_END_REPAIR_STATUS, []))
            throw new ForbiddenHttpException($this->noAccessMessage);

        $model->status = User::STATUS_END_REPAIR;

        if ($model->save(false))
            Yii::$app->session->setFlash('info', Html::icon('alert') . ' Для пользователя "' . $model->username . '" установлен статус "' . User::getStatusList()[$model->status] . '"');
        else
            Yii::$app->session->setFlash('error', Html::icon('alert') . ' Возникли ошибки при изменениии статуса');

        return $this->redirect([Yii::$app->user->getReturnUrl()]);
    }

    public function actionGiveGift()
    {
        if (!Yii::$app->request->isPjax)
            throw new NotFoundHttpException('Страница не найдена');

        // Проверка доступа
        if (!Yii::$app->user->can(Rights::GIVE_GIFT, []))
            throw new ForbiddenHttpException($this->noAccessMessage);

        $model = new FamilyGiveGiftForm();
        $familyPoints = null;
        $journalPoints = null;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                /**@var Journal $journal */
                if (($journal = Journal::findOne($model->journal_id)) === null) {
                    throw new NotFoundHttpException("Journal not found");
                }

                $db = Yii::$app->db;

                $transaction = $db->beginTransaction();
                try {
                    $gift = new Gift(['scenario' => Gift::SCENARIO_CREATE]);
                    $gift->from_family_id = Yii::$app->user->id;
                    $gift->to_family_id = $journal->user_id;
                    $gift->journal_id = $journal->id;
                    $gift->points = $model->points;
                    $gift->save();

                    /** @var User $family */
                    $family = User::find()->withPoints()->where(['id' => Yii::$app->user->id])->one();

                    if (($familyPoints = $family->points) < 0) {
                        $model->addError('points', 'У Вас нет столько баллов, попробуйте подарить меньше');
                        $transaction->rollBack();
                    } else {
                        $transaction->commit();
                        $model->saved = true;
                        $journalPoints = $journal->points . ($journal->getGiftPoints() > 0 ? '+' . $journal->getGiftPoints() : '');
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }
        }

        return $this->render('families/__form_give_gift', [
            'model' => $model,
            'familyPoints' => $familyPoints,
            'journalPoints' => $journalPoints,
        ]);
    }

    /**
     * Установить/снять статус "Профи"
     * @param integer $id
     * @return Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionSetProfStatus($id)
    {
        /**@var User $model */
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if ((($model = $model::findOne($id)) === null) || ($model->role != User::ROLE_FAMILY)) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }
        $model->setScenario('setIsProf');

        // Проверка доступа
        if (!Yii::$app->user->can(Rights::FAMILY_SET_PROF_STATUS, []))
            throw new ForbiddenHttpException($this->noAccessMessage);

        $model->is_prof = !$model->is_prof;

        if ($model->save(false)) {
            if ($model->is_prof)
                Yii::$app->session->setFlash('info', Html::icon('alert') . ' Для пользователя "' . $model->username . '" установлен статус "Профи"');
            else
                Yii::$app->session->setFlash('info', Html::icon('alert') . ' Для пользователя "' . $model->username . '" снят статус "Профи"');
        } else
            Yii::$app->session->setFlash('error', Html::icon('alert') . ' Возникли ошибки при изменениии статуса "Профи"');

        return $this->redirect([Yii::$app->user->getReturnUrl()]);
    }

    public function actionSubscribeIt($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        /**@var User $model */
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if (($model = $model::findOne($id)) === null) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }

        /** @var User $user */
        $user = Yii::$app->user->identity;
        $subscribedIt = in_array($model->id, $user->getSubscriptionToUserIds());

        if ($subscribedIt)
            $user->unlink('subscriptionToUsers', $model, true);
        else
            $user->link('subscriptionToUsers', $model);

        return [
            'status' => 'success',
            'currentUserSubscribedIt' => !$subscribedIt,
        ];
    }

    //формируем json адресов семей в зависимости от города
    //и отправляем ajax на виджет карты
    public function actionCityLocations()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $cityName = Yii::$app->request->post()['city'];
        $city = City::find()->where(['name' => $cityName])->one();
        $locations = MapApiHelper::formLocationsCityData($city->usersAndLocations);

        return $locations;
    }

    //вывод странички с картой
    public function actionLocations()
    {
        if (Yii::$app->request->post('cancel') !== null) {
            return $this->redirect(['index']);
        }

        // Проверка доступа
        if (!Yii::$app->user->can(Rights::SHOW_FAMILY_LOCATIONS))
            throw new ForbiddenHttpException($this->noAccessMessage);

        return $this->render('locations/view');
    }
}
