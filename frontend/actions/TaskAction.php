<?php
namespace frontend\actions;

use common\components\actions\ModelAction;
use common\components\ActiveRecord;
use common\models\Task;
use common\models\TaskPhoto;
use common\models\TaskUser;
use ReflectionClass;
use Yii;
use yii\helpers\ArrayHelper;


class TaskAction extends ModelAction
{
    /**
     * @var string view for action
     */
    public $view = '';

    /**
     * @var string scenario for models
     */
    public $modelScenario = 'update';

    /**
     * @var string|array url for return after success update
     */
    public $returnUrl = 'index';

    public function getModelClass()
    {
        return Task::className();
    }

    /**
     * @param Task $model
     * @param bool|true $validate
     * @return bool
     */
    protected function processPostData(&$model, $validate = true)
    {
        $isValid = true;

        // Фото
        if (!$this->processPhotosPostData($model, TaskPhoto::className(), 'photos', $validate)) {
            $isValid = false;
        }

        // Пользователи
        $familiesData = ArrayHelper::getValue(Yii::$app->request->post('Task'), 'families', null);
        if (($familiesData != null) and (is_array($familiesData))) {
            $_families = array();

            foreach ($familiesData as $familyItem) {
                $taskUserModel = new TaskUser(['scenario'=>'create']);
                $taskUserModel->load($familyItem, '');

                if ($validate)
                    if (!$taskUserModel->validate())
                        $isValid = false;

                $_families[] = $taskUserModel;
            }
            $model->populateRelation('families', $_families);
        }
        else {
            $model->populateRelation('families', []);
        }

        // Валидация
        return (!$validate)or($validate and $model->validate() and $isValid);
    }

    /**
     * @param Task $model
     * @param string $photoModelClass
     * @param $photoRelationName
     * @param bool $validate
     * @return bool
     * @internal param ActiveRecord $models
     * @internal param string $photoPostName
     */
    protected function processPhotosPostData(&$model, $photoModelClass, $photoRelationName, $validate)
    {
        $isValid = true;

        $photosData = ArrayHelper::getValue(Yii::$app->request->post($model->formName()), $photoRelationName, null);
        if (($photosData != null) and (is_array($photosData))) {
            $_photos = array();

            foreach ($photosData as $photoData) {
                if ($photoData['deleted'] == 1)
                    continue;

                /**@var ActiveRecord $photoModel*/
                $photoModel = (new ReflectionClass($photoModelClass))->newInstance();
				if (isset($photoData['id']) && (intval($photoData['id']) > 0)) {
                    $photoModel = $photoModel::findOne($photoData['id']);
                }

                $photoModel->load($photoData, '');

                if ($validate)
                    if (!$photoModel->validate())
                        $isValid = false;

                $_photos[] = $photoModel;
            }
            $model->populateRelation($photoRelationName, $_photos);
        }

        return $isValid;
    }

    /**
     * @param Task $model
     * @return bool
     */
    protected function savePostData(&$model)
    {
        $transaction = Yii::$app->db->beginTransaction();
        if ($model->save()) {
            // Сохраняем фото
            if (!$this->savePhotosPostData($model, 'photos')) {
                $transaction->rollBack();
                return false;
            }

            // Сохраняем семьи
            if (!$model->isNewRecord) {
                $newIdList = ArrayHelper::getColumn($model->families, function($e){return $e->user_id;});
                /**@var Task $prevModel*/
                $prevModel = (new ReflectionClass($this->getModelClass()))->newInstance();
                $prevModel = $prevModel::findOne($model->id);

                foreach ($prevModel->families as $family) {
                    if (array_search($family->user_id, $newIdList) === false) {
                        TaskUser::deleteAll(['user_id'=>$family->user_id, 'task_id'=>$model->id]);
                    }
                }
            }

            foreach ($model->families as $family) {
                $tu = TaskUser::findOne(['user_id'=>$family->user_id, 'task_id'=>$model->id]);
                if ($tu == null)
                    $model->link('families', $family);
            }

            $transaction->commit();
            return true;
        }
        $transaction->rollBack();
    }

    /**
     * @param ActiveRecord $model
     * @param $photoRelationName
     * @return bool
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    protected function savePhotosPostData(&$model, $photoRelationName)
    {
        // Сохраняем фото
        $savedPhotosIdArray = [];
        foreach ($model->{$photoRelationName} as $photo) {
            /**@var ActiveRecord $photo*/
            $photo->task_id = $model->id;

            if (!$photo->save()) {
                return false;
            }
            $savedPhotosIdArray[] = $photo->id;
        }
        // Удаляем удаленные
        $where = (count($savedPhotosIdArray) > 0) ? 'id NOT IN ('.implode(',', $savedPhotosIdArray).')' : '';

        $photosForDelete = $model->getRelation($photoRelationName)->andWhere($where)->all();
        foreach ($photosForDelete as $photo) {
            $photo->delete();
        }

        return true;
    }
}