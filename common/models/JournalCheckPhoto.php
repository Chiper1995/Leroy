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
 * Class JournalCheckPhoto
 * @package common\models
 *
 * @property integer $id
 * @property integer $journal_id
 * @property string $photo
 * @property integer $updated_at
 *
 * @property Journal $journal
 *
 * @mixin PhotoThumbBehavior
 */
class JournalCheckPhoto extends ActiveRecord implements IImageModel
{
    public function getJournal()
    {
        return $this->hasOne(Journal::className(), ['id' => 'journal_id'])->inverseOf('photos');
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
            'journal_id' => 'Дневник',
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
        return Yii::getAlias('@files/check_photo');
    }

    static public function getTempPath()
    {
        return Yii::getAlias('@files/check_photo/temp');
    }

    static public function getAllowedExtensions()
    {
        return ['jpg', 'jpeg', 'png', 'gif'];
    }

    static public function getMaxSize()
    {
        return isset(Yii::$app->params['journalPhoto.maxSize']) ? Yii::$app->params['journalPhoto.MaxSize'] : 5*1024*1024;
    }

    static public function getMaxWidth()
    {
        return isset(Yii::$app->params['journalPhoto.maxWidth']) ? Yii::$app->params['journalPhoto.maxWidth'] : 1024;
    }

    static public function getMaxHeight()
    {
        return isset(Yii::$app->params['journalPhoto.maxHeight']) ? Yii::$app->params['journalPhoto.maxHeight'] : 1024;
    }

    static public function getTempUrlPath()
    {
        return Yii::getAlias('@web/files/check_photo/temp');
    }

    static public function getUrlPath()
    {
        return Yii::getAlias('@web/files/check_photo');
    }
}