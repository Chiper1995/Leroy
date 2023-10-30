<?php
namespace common\components;

use Yii;
use yii\base\Behavior;
use yii\log\Logger;

/**
 * Class PhotoThumbBehavior
 * @property string $photoThumb
 */
class PhotoThumbBehavior extends Behavior
{
    public $photoAttribute = 'photo';

    public $photoPath = '@frontend/web/files/photo';

    public $thumbPath = '@frontend/web/files/thumbs';

    public $thumbUrl = '/files/thumbs';

    public $noPhotoImage = '@web/files/no_photo.gif';

    public function getPhotoThumb($width, $height, $returnFilePath = false, $fullSize = false)
    {
        $photo = $this->getPhotoPath();

        // Фото отсутствует
        if ((!is_file($photo))or(!file_exists($photo))) {
            Yii::getLogger()->log('Image not found: '.$photo, Logger::LEVEL_WARNING);
            return $this->noPhotoImage;
        }

        // Путь до миниатюр
        $thumbPath = $this->getPhotoThumbPath($photo);

        // Генерим
        if (($width != null) and ($height != null))
        {
            if ($fullSize) {
                $photoPath = $this->createFullImageThumb($photo, $thumbPath, $width, $height);
            }
            else {
                $photoPath = $this->createThumb($photo, $thumbPath, $width, $height);
            }

            return $returnFilePath ? $thumbPath . '/' . $photoPath : $this->thumbUrl . '/' . $this->hash($photo) . '/' . $photoPath;
        }
        else {
            return $returnFilePath ? $this->getPhotoPath() : $this->owner->{$this->photoAttribute};
        }
    }

    protected function getPhotoPath()
    {
        $photo = $this->owner->{$this->photoAttribute};

        // Путь до фото
        if (($photoPath = Yii::getAlias($this->photoPath)) !== false)
            $photoPath = $photoPath.DIRECTORY_SEPARATOR;
        else
            $photoPath = $this->photoPath;

        return $photoPath.$photo;
    }

    public function getPhotoThumbPath($photo = null)
    {
        if ($photo === null)
            $photo = $this->getPhotoPath();

        // Путь до миниатюр
        if (($thumbPath = Yii::getAlias($this->thumbPath)) === false)
            $thumbPath = $this->thumbPath.DIRECTORY_SEPARATOR;
        else
            $thumbPath = $thumbPath.DIRECTORY_SEPARATOR;

        // Добавляем путь до изображения
        $hashPath = $this->hash($photo);

        return $thumbPath.$hashPath;
    }

    protected function createThumb($photo, $thumbPath, $width, $height)
    {
        // Подпапка с размерами
        $thumbPath = $thumbPath.DIRECTORY_SEPARATOR.$width.'x'.$height.DIRECTORY_SEPARATOR;

        $this->_createThumb($photo, $thumbPath, $width, $height, function ($photo, $thumbPhoto, $width, $height){
            Thumbnail::CreateThumb($photo, $thumbPhoto, $width, $height);
        });

        return $width.'x'.$height.'/'.basename($photo);
    }

    protected function createFullImageThumb($photo, $thumbPath, $width, $height)
    {
        // Подпапка с размерами
        $thumbPath = $thumbPath.DIRECTORY_SEPARATOR.$width.'x'.$height.'_full'.DIRECTORY_SEPARATOR;

        $this->_createThumb($photo, $thumbPath, $width, $height, function ($photo, $thumbPhoto, $width, $height){
            Thumbnail::CreateFullImageThumb($photo, $thumbPhoto, $width, $height);
        });

        return $width.'x'.$height.'_full/'.basename($photo);
    }

    protected function _createThumb($photo, $thumbPath, $width, $height, $function)
    {
        // Создаем папку если её нет
        if (!is_dir($thumbPath)) mkdir($thumbPath, 0777, true);

        // Имя миниатюры
        $thumbPhoto = $thumbPath.basename($photo);
        if ((!file_exists($thumbPhoto))or(filemtime($thumbPhoto)<filemtime($photo)))
        {
            ini_set("memory_limit","256M");
            $function($photo, $thumbPhoto, $width, $height);
        }
    }

    protected function hash($path)
    {
        return sprintf('%x',crc32($path));
    }
}