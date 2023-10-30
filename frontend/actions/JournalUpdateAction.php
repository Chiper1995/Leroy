<?php
namespace frontend\actions;

use common\models\Journal;
use common\models\JournalType;
use common\models\JournalOtherRoomType;
use common\models\RoomRepair;
use common\models\User;
use common\models\UserLocation;
use common\models\Task;
use common\models\City;
use common\rbac\Rights;
use ReflectionClass;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class JournalUpdateAction extends JournalAction
{
    /**
     * @var string view for action
     */
    public $view = 'update';

	/**
	 * @param integer $id
	 * @return array|string|Response
	 * @throws NotFoundHttpException
	 * @throws \ErrorException
	 * @throws \ReflectionException
	 * @throws \yii\web\ForbiddenHttpException
	 * @throws \yii\db\Exception
	 */
    public function run($id)
    {
        if (Yii::$app->request->post('cancel') !== null) {
            return $this->controller->goBack(Url::toRoute([$this->returnUrl]));
        }

        /**@var Journal $model*/
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if (($model = $model::findOne($id)) === null) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }

        $model->setScenario($this->modelScenario);

        // Проверка доступа
        $this->checkAccess($model);

        // Если запись по заданию, то присваиваем нужный тип
        if ($model->task instanceof Task) {
            $model->populateRelation('journalTypes', [JournalType::findOne(JournalType::TASK_JOURNAL_TYPE)]);
        }

        // Если ajax
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $wrongOtherRoom = $this->needFieldForModel('other-room');
            $this->saveOtherRoom($model);

            if (Yii::$app->user->can(Rights::EDIT_MY_JOURNAL, ['journal'=>$model]))
                $model->updateVersionToken();

            if ($this->processPostData($model)) {
                if ($wrongOtherRoom) {
                    $model->status = Journal::STATUS_DRAFT;
                    $model->save();
                }

                if ($model->status == Journal::STATUS_ON_CHECK) {
                    $type = ArrayHelper::getValue(Yii::$app->request->post('Journal'), 'journalTypes', null);
                    if ($type != "")
                    {
                        if ($type[0] === "2" && count($type) == 1) {
                            /**@var User $user */
                            $user = Yii::$app->user->identity;
                            $user->flag_buy_post = 1;
                            $user->save(false);
                        }
                    }
                }

                if ($this->savePostData($model) && !$wrongOtherRoom) {

                    return $this->controller->goBack(Url::toRoute([$this->returnUrl]));
                } else {
                    Yii::$app->session->setFlash('error', Html::icon('alert') . ' Возникла ошибка при сохранении');
                }
            } else {
                Yii::$app->session->setFlash('error', Html::icon('alert') . ' Проверьте правильность заполнения полей');
            }
        }

        $model->saveViewed(Yii::$app->user->id);

        return $this->controller->render($this->view, [
            'model' => $model,
            'wrongOtherRoom' => isset($wrongOtherRoom)? $wrongOtherRoom : null,
        ]);
    }

    private function needFieldForModel($field)
    {
        $request = Yii::$app->request->post();

        $workType = JournalType::WORK_JOURNAL_TYPE;
        $journalTypes = $request['Journal']['journalTypes'];
        if (empty($journalTypes)) return false;
        if (!in_array($workType, $journalTypes)) return false;

        $repairRooms = $request['Journal']['repairRooms'];
        if (empty($repairRooms) && $field == 'other-room') return false;
        $otherTypeRoom = RoomRepair::getOtherRoomTypeId();
        if (!in_array($otherTypeRoom, $repairRooms) && $field == 'other-room')
            return false;

        if (empty($request[$field])) return true;

        return false;
    }

    private function saveOtherRoom($model)
    {
        $request = Yii::$app->request->post();
        if (empty($request['other-room'])) return false;

        $otherRoom = new JournalOtherRoomType();
        $otherRoom->journal_id = $model->id;
        $otherRoom->room = $request['other-room'];
        $otherRoom->save();
        return $otherRoom;
    }
}
