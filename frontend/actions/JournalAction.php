<?php
namespace frontend\actions;

use common\components\actions\ModelAction;
use common\components\ActiveRecord;
use common\events\AppEvents;
use common\models\Journal;
use common\models\JournalCheckPhoto;
use common\models\JournalGoods;
use common\models\JournalPhoto;
use common\models\JournalType;
use common\models\JournalTag;
use common\models\RoomRepair;
use common\models\Task;
use common\models\WorkRepair;
use common\rbac\Rights;
use ReflectionClass;
use Yii;
use yii\base\Event;
use yii\helpers\ArrayHelper;

class JournalAction extends ModelAction
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
    public $returnUrl = 'my-journal';

    public function getModelClass()
    {
        return Journal::className();
    }

    /**
     * @param Journal $model
     * @param bool|true $validate
     * @return bool
     */
    protected function processPostData(&$model, $validate = true)
    {
        $isValid = true;

        if ($model->isNewRecord)
            $model->user_id = Yii::$app->user->id;

        // Присваиваем статус
        if ($model->status != Journal::STATUS_PUBLISHED) {
            if (Yii::$app->request->post('publish') !== null) {
                $model->status = Journal::STATUS_ON_CHECK;
            }
            else {
                $model->status = Journal::STATUS_DRAFT;
            }
        }

        // Фото
        if (!$this->processPhotosPostData($model, JournalPhoto::className(), 'photos', $validate)) {
            $isValid = false;
        }

        // Фото чеков
        if (!$this->processPhotosPostData($model, JournalCheckPhoto::className(), 'checkPhotos', $validate)) {
            $isValid = false;
        }

        // Товары
        $_goods = array();
        $goodsData = ArrayHelper::getValue(Yii::$app->request->post('Journal'), 'goods', null);
        if (($goodsData != null) and (is_array($goodsData))) {
            foreach ($goodsData as $goodsItem) {
                $journalGoodsModel = new JournalGoods([
                    'scenario'=>($model->status == Journal::STATUS_DRAFT) ?
                        JournalGoods::SCENARIO_DRAFT_CREATE :
                        JournalGoods::SCENARIO_CREATE
                ]);
                $journalGoodsModel->load($goodsItem, '');

                if ($validate)
                    if (!$journalGoodsModel->validate())
                        $isValid = false;

                $_goods[] = $journalGoodsModel;
            }
        }
        $model->populateRelation('goods', $_goods);

        // Тип поста
        if ($model->task instanceof Task) {
            // Если запись по заданию, то присваиваем нужный тип
            $model->populateRelation('journalTypes', [JournalType::findOne(JournalType::TASK_JOURNAL_TYPE)]);
        }
        else {
            $_journalTypes = array();
            $journalTypesData = ArrayHelper::getValue(Yii::$app->request->post('Journal'), 'journalTypes', null);
            if (($journalTypesData != null) and (is_array($journalTypesData))) {
                foreach ($journalTypesData as $journalTypeId) {
                    if (($journalType = JournalType::findOne($journalTypeId)) != null) {
                        $_journalTypes[] = $journalType;
                    }
                }
            }
            $model->populateRelation('journalTypes', $_journalTypes);
        }

        // Работы
        $_repairWorks = array();
        if (in_array(1, ArrayHelper::getColumn($model->journalTypes, 'id'))) {
            $repairWorksData = ArrayHelper::getValue(Yii::$app->request->post('Journal'), 'repairWorks', null);
            if (($repairWorksData != null) and (is_array($repairWorksData))) {
                foreach ($repairWorksData as $repairWorkId) {
                    if (($repairWork = WorkRepair::findOne($repairWorkId)) != null) {
                        $_repairWorks[] = $repairWork;
                    }
                }
            }
        }
        $model->populateRelation('repairWorks', $_repairWorks);

        // Помещения
        $_repairRooms = array();
        $repairRoomsData = ArrayHelper::getValue(Yii::$app->request->post('Journal'), 'repairRooms', null);
        if (($repairRoomsData != null) and (is_array($repairRoomsData))) {
            foreach ($repairRoomsData as $repairRoomId) {
                if (($repairRoom = RoomRepair::findOne($repairRoomId)) != null) {
                    $_repairRooms[] = $repairRoom;
                }
            }
        }
        $model->populateRelation('repairRooms', $_repairRooms);

        // Тэги
        $_journalTags = array();
        $journalTagsData = ArrayHelper::getValue(Yii::$app->request->post('Journal'), 'journalTags', null);
        if (($journalTagsData != null) and (is_array($journalTagsData))) {
            foreach ($journalTagsData as $journalTagId) {
                if (($journalTag = JournalTag::findOne($journalTagId)) != null) {
                    $_journalTags[] = $journalTag;
                }
            }
        }
        $model->populateRelation('journalTags', $_journalTags);

        // Валидация
        return
            (!$validate)
            || (
                $validate && $model->validate() && $isValid
                && (($model->status == Journal::STATUS_DRAFT) || $model->validate(['journalTypes', 'repairWorks', 'repairRooms', 'goods', 'photos']))
            );
    }

	/**
	 * @param Journal $model
	 * @param string $photoModelClass
	 * @param $photoRelationName
	 * @param bool $validate
	 * @return bool
	 * @internal param ActiveRecord $models
	 * @internal param string $photoPostName
	 * @throws \ReflectionException
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

                if ($photoModel instanceof JournalPhoto) {
                    if ($model->status == Journal::STATUS_PUBLISHED) {
                        if (
                            (Yii::$app->user->can(Rights::SHOW_JOURNAL_ON_CHECK_NOTIFICATION, ['journal'=>$model]))
                            || (Yii::$app->user->can(Rights::SHOW_JOURNAL_BY_TASK_ON_CHECK_NOTIFICATION, ['journal'=>$model]))
                        ) {
                            $photoModel->status = JournalPhoto::STATUS_PUBLISHED;
                        } else {
                            $photoModel->status = JournalPhoto::STATUS_ON_CHECK;
                        }
                    } else {
                        $photoModel->status = $model->status;
                    }
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
	 * @param Journal $model
	 * @return bool
	 * @throws \ErrorException
	 * @throws \ReflectionException
	 * @throws \yii\db\Exception
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

            // Сохраняем фото чеков
            if (!$this->savePhotosPostData($model, 'checkPhotos')) {
                $transaction->rollBack();
                return false;
            }

            // Сохраненная ранее модель
            /* @var Journal $prevModel */
            $prevModel = null;
            if (!$model->isNewRecord) {
                $prevModel = (new ReflectionClass($this->getModelClass()))->newInstance();
                $prevModel = $prevModel::findOne($model->id);
            }

            // Сохраняем товары
            if ($prevModel != null) {
                $prevModel->unlinkAll('goods', true);
            }
            foreach ($model->goods as $goods) {
                $model->link('goods', $goods);
            }

            // Сохраняем Тип поста
            if ($prevModel != null) {
                $prevModel->unlinkAll('journalTypes', true);
            }
            foreach ($model->journalTypes as $journalType) {
                $model->link('journalTypes', $journalType);
            }

            // Сохраняем Помещения Ремонта
            if ($prevModel != null) {
                $prevModel->unlinkAll('repairRooms', true);
            }
            foreach ($model->repairRooms as $repairRoom) {
                $model->link('repairRooms', $repairRoom);
            }

            // Сохраняем Теги поста
            if ($prevModel != null) {
                $prevModel->unlinkAll('journalTags', true);
            }
            foreach ($model->journalTags as $journalTag) {
                $model->link('journalTags', $journalTag);
            }

            // Обновляем количество баллов если запись опубликова и изменили тип поста
            if ($model->status === Journal::STATUS_PUBLISHED) {
				Yii::$app->trigger(AppEvents::EVENT_JOURNAL_ON_TYPE_CHANGED, new Event(['sender' => $model]));
			}

            // Сохраняем Работы
            if ($prevModel != null) {
                $prevModel->unlinkAll('repairWorks', true);
            }
            foreach ($model->repairWorks as $repairWork) {
                $model->link('repairWorks', $repairWork);
            }

            $transaction->commit();
            return true;
        }
        $transaction->rollBack();
    }

    /**
     * @param Journal $model
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
            /**@var JournalPhoto $photo*/
            $photo->journal_id = $model->id;
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
