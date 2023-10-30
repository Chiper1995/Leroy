<?php
namespace frontend\controllers;

use common\components\controllers\BaseController;
use common\components\controllers\IModelController;
use common\events\AppEvents;
use common\models\Dialog;
use common\models\notifications\dialog\DialogNewMessageNotification;
use common\rbac\Rights;
use frontend\models\dialog\AddMessageForm;
use frontend\models\dialog\NewDialogForm;
use frontend\models\dialog\MyDialogsSearch;
use Yii;
use yii\base\Event;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;


class DialogController extends BaseController implements IModelController
{
    public $layout = 'main';

    public function getModelClass()
    {
        return Dialog::className();
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

    /**
     * Просмотр диалогов
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can(Rights::SHOW_SEND_MESSAGE_NOTIFICATION, []))
            throw new ForbiddenHttpException($this->noAccessMessage);

        $searchModel = new MyDialogsSearch();
        $dataProvider = $this->getDialogListDataProvider($searchModel);

        return $this->render('dialogs', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Создание нового диалога
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionNewDialog()
    {
        $createdDialogId = null;
        $newDialogForm = new NewDialogForm();

        if (!Yii::$app->user->can(Rights::CREATE_DIALOGS, []))
            throw new ForbiddenHttpException($this->noAccessMessage);

        if (Yii::$app->request->post('send_message') !== null) {
            if ($newDialogForm->load(Yii::$app->request->post()) and $newDialogForm->validate()) {
                if (($createdMessage = $newDialogForm->createDialog(Yii::$app->user->id))!==null) {
                    $createdDialogId = $createdMessage->dialog_id;
                    // Вызываем событие //comment 28/05/2021
                    //Yii::$app->trigger(AppEvents::EVENT_SEND_MESSAGE, new Event(['sender' => $createdMessage]));
                    // Обновляем форму
                    $newDialogForm = new NewDialogForm();
                }
                else {
                    $newDialogForm->addError('message', 'Возникла ошибка при сохранении, попробуйте позже');
                }
            }
        }

        return $this->render('new_dialog', [
            'newDialogForm' => $newDialogForm,
            'createdDialogId' => $createdDialogId,
        ]);
    }


    /**
     * Создание нового диалога с куратором
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionNewTicket()
    {
        $createdDialogId = null;
        $newDialogForm = new NewDialogForm();

        if (!Yii::$app->user->can(Rights::CREATE_DIALOGS, []))
            throw new ForbiddenHttpException($this->noAccessMessage);

        if (Yii::$app->request->post('send_message') !== null) {
            if ($newDialogForm->load(Yii::$app->request->post()) and $newDialogForm->validate()) {
                if (($createdMessage = $newDialogForm->createDialog(Yii::$app->user->id))!==null) {
                    $createdDialogId = $createdMessage->dialog_id;
                    // Вызываем событие
                    Yii::$app->trigger(AppEvents::EVENT_SEND_MESSAGE, new Event(['sender' => $createdMessage]));
                    // Обновляем форму
                    $newDialogForm = new NewDialogForm();
                }
                else {
                    $newDialogForm->addError('message', 'Возникла ошибка при сохранении, попробуйте позже');
                }
            }
        }

        return $this->render('new_ticket', [
            'newDialogForm' => $newDialogForm,
            'createdDialogId' => $createdDialogId,
        ]);
    }

    /**
     * Просмотр диалога и добавление сообщений
     * @param $dialogId
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionViewDialog($dialogId)
    {
        /**@var Dialog $dialog*/
        if (($dialog = Dialog::findOne($dialogId)) == null)
            throw new NotFoundHttpException("This dialog not found");

        // Есть ли пользователь в диалоге
        if ($dialog->getAllUsers()->andWhere(['id'=>Yii::$app->user->id])->count() == 0)
            throw new ForbiddenHttpException($this->noAccessMessage);

        $readOnly = $dialog->getUsers()->andWhere(['id'=>Yii::$app->user->id])->count() == 0;

        // Убираем уведомления по этому диалогу
        DialogNewMessageNotification::setViewedByDialogId(Yii::$app->user->id, $dialogId);

        // Готовим форму отправки сообщения
        if (!$readOnly) {
            $addMessageForm = new AddMessageForm();
            if (Yii::$app->request->post('send_message') !== null) {
                if ($addMessageForm->load(Yii::$app->request->post()) and $addMessageForm->validate()) {
                    if (($createdMessage = $addMessageForm->saveMessage($dialog, Yii::$app->user->id))!==null) {
                        // Вызываем событие
                        Yii::$app->trigger(AppEvents::EVENT_SEND_MESSAGE, new Event(['sender' => $createdMessage]));
                        // Обновляем форму
                        $addMessageForm = new AddMessageForm();
                    }
                    else {
                        $addMessageForm->addError('message', 'Возникла ошибка при сохранении, попробуйте позже');
                    }
                }
            }
        } else {
           $addMessageForm = null;
//DialogNewMessageNotification::setViewedByDialogId(Yii::$app->user->id, $dialogId);

       }


        return $this->render('view_dialog', [
            'viewedDialog' => $dialog,
            'addMessageForm' => $addMessageForm,
        ]);
    }

    /**
     * @param MyDialogsSearch $searchModel
     * @return \yii\data\ActiveDataProvider
     */
    private function getDialogListDataProvider($searchModel)
    {
        $dataProviderConfig = [
            'pagination' => [
                'pageSize' => 20,
                'defaultPageSize' => 20,
            ],
            'sort' => [
                'attributes' => [
                    'updated_at',
                ],
                'defaultOrder' => [
                    'updated_at' => SORT_DESC,
                ]
            ]
        ];

        $dataProvider = $searchModel->search($this->getModelClass(), Yii::$app->request->queryParams, $dataProviderConfig);

        return $dataProvider;
    }
}
