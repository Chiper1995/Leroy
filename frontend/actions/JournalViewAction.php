<?php
/**
 * Created by PhpStorm.
 * User: arshatilov
 * Date: 02.07.2018
 * Time: 10:51
 */

namespace frontend\actions;


use common\events\AppEvents;
use common\models\Journal;
use common\models\JournalPhoto;
use common\rbac\Rights;
use yii\base\Event;
use yii\bootstrap\Html;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;
use ReflectionClass;

class JournalViewAction extends JournalAction
{
    /**
     * @var string view for action
     */
    public $view = 'view';

    /**
     * @var string scenario for models
     */
    public $modelScenario = 'view';

    /**
     * @var string|array url for return after success update
     */
    public $returnUrl = 'index';

    /**
     * @param $id
     * @return array|string|Response
     * @throws NotFoundHttpException
     */
    public function run($id)
    {
        if (Yii::$app->request->post('cancel') !== null) {
            return $this->controller->redirect([$this->returnUrl]);
        }

        /**@var Journal $model*/
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if (($model = $model::findOne($id)) === null) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }

        $model->setScenario($this->modelScenario);

        // Проверка доступа
        $this->checkAccess($model);

        if (Yii::$app->user->can(Rights::EDIT_MY_JOURNAL_PHOTO, ['journal' => $model])) {
            if ($model->load(Yii::$app->request->post())) {
                if ($this->processPostData($model)) {
                    if ($this->savePostData($model)) {
                        Yii::$app->trigger(AppEvents::EVENT_JOURNAL_PHOTO_ON_CHECK, new Event(['sender' => $model]));
                        return $this->controller->goBack(Url::toRoute([$this->returnUrl]));
                    } else {
                        Yii::$app->session->setFlash('error', Html::icon('alert') . ' Возникла ошибка при сохранении');
                    }
                } else {
                    Yii::$app->session->setFlash('error', Html::icon('alert') . ' Проверьте правильность заполнения полей');
                }
            }
        }

        $model->saveViewed(Yii::$app->user->id);

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }

    protected function processPostData(&$model, $validate = true) {
        $isValid = true;

        // Фото
        if (!$this->processPhotosPostData($model, JournalPhoto::className(), 'photos', $validate)) {
            $isValid = false;
        }

        // Валидация
        return
            (!$validate)
            || (
                $validate && $isValid
            );
    }

    protected function savePostData(&$model)
    {
        $transaction = Yii::$app->db->beginTransaction();

        if (!$this->savePhotosPostData($model, 'photos')) {
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        return true;
    }

    protected function savePhotosPostData(&$model, $photoRelationName)
    {
        // Сохраняем фото
        $savedPhotosIdArray = [];
        foreach ($model->{$photoRelationName} as $photo) {
            /**@var ActiveRecord $photo*/
            $photo->journal_id = $model->id;

            if (!$photo->save()) {
                return false;
            }
            $savedPhotosIdArray[] = $photo->id;
        }
        // Удаляем удаленные
        $where = (count($savedPhotosIdArray) > 0) ? 'id NOT IN ('.implode(',', $savedPhotosIdArray).')' : '';

        $photosForDelete = $model->getRelation($photoRelationName)
            ->andWhere($where)
            ->andWhere(['<>', 'status', JournalPhoto::STATUS_PUBLISHED])
            ->all();
        foreach ($photosForDelete as $photo) {
            $photo->delete();
        }

        return true;
    }
}