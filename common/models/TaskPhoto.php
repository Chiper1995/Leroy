<?php
namespace common\models;

use common\components\ActiveRecord;
use common\components\PhotoThumbBehavior;
use common\models\interfaces\IImageModel;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Yii;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;

/**
 * Class TaskPhoto
 * @package common\models
 *
 * @property integer $id
 * @property integer $task_id
 * @property string $photo
 * @property integer $updated_at
 *
 * @property Task $task
 *
 * @mixin PhotoThumbBehavior
 */
class TaskPhoto extends ActiveRecord implements IImageModel
{
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id'])->inverseOf('photos');
    }

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'PhotoThumbBehavior' => [
                    'class' => PhotoThumbBehavior::className(),
                    'photoPath' => self::getPath(),
                ],
            ]
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['photo', 'filter', 'filter' => 'trim'],
            ['photo', 'required'],
            ['photo', 'string', 'min' => 3, 'max' => 255],
        ];
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['photo'];
        $scenarios['update'] = ['photo'];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'task_id' => 'Задание',
            'photo' => 'Фото',
            'updated_at' => 'Последнее обновление',
        );
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert)
            if (!rename(self::getTempPath().'/'.$this->photo, self::getPath().'/'.$this->photo)) {
                throw new ErrorException("Ошибка при сохранении фото [1]");
            }
    }

    public function afterDelete()
    {
        if (file_exists($this->getPhotoThumbPath()) and is_dir($this->getPhotoThumbPath())) {
            $di = new RecursiveDirectoryIterator($this->getPhotoThumbPath(), FilesystemIterator::SKIP_DOTS);
            $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($ri as $file) {
                $file->isDir() ?  rmdir($file) : unlink($file);
            }
            rmdir($this->getPhotoThumbPath());
        }

        if (file_exists(self::getPath().'/'.$this->photo)) {
            unlink(self::getPath().'/'.$this->photo);
        }
        parent::afterDelete();
    }

    static public function getPath()
    {
        return Yii::getAlias('@files/task_photo');
    }

    static public function getTempPath()
    {
        return Yii::getAlias('@files/task_photo/temp');
    }

    static public function getAllowedExtensions()
    {
        return ['jpg', 'jpeg', 'png', 'gif'];
    }

    static public function getMaxSize()
    {
        return isset(Yii::$app->params['taskPhoto.maxSize']) ? Yii::$app->params['taskPhoto.MaxSize'] : 5*1024*1024;
    }

    static public function getMaxWidth()
    {
        return isset(Yii::$app->params['taskPhoto.maxWidth']) ? Yii::$app->params['taskPhoto.maxWidth'] : 1024;
    }

    static public function getMaxHeight()
    {
        return isset(Yii::$app->params['taskPhoto.maxHeight']) ? Yii::$app->params['taskPhoto.maxHeight'] : 1024;
    }

    static public function getTempUrlPath()
    {
        return Yii::getAlias('@web/files/task_photo/temp');
    }

    static public function getUrlPath()
    {
        return Yii::getAlias('@web/files/task_photo');
    }
}