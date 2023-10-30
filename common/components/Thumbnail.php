<?php
namespace common\components;


use Imagick;
use yii\base\ErrorException;

class Thumbnail
{
    const BACKGROUND_COLOR = '#d2e1ec';

    private $_filePath;

    /**
     * Уменьшить изображение, сохранив пропорции
     * @param string $filePath
     * @param int $width
     * @param int $height
     */
    public static function ReduceImage($filePath, $width, $height)
    {
        $t = new Thumbnail($filePath);
        $t->outputReduce($width, $height);
    }

    /**
     * Создать уменьшенную копию изображения
     * @param string $filePath
     * @param string $newFileName
     * @param int $width
     * @param int $height
     */
    public static function CreateThumb($filePath, $newFileName, $width, $height)
    {
        $t = new Thumbnail($filePath);
        $t->outputThumb($width, $height, $newFileName);
    }

    /**
     * Создать уменьшенную копию изображения
     * @param string $filePath
     * @param string $newFileName
     * @param int $width
     * @param int $height
     */
    public static function CreateFullImageThumb($filePath, $newFileName, $width, $height)
    {
        $t = new Thumbnail($filePath);
        $t->outputFullImageThumb($width, $height, $newFileName);
    }

    public function __construct($filePath)
    {
        ini_set('memory_limit', '300M');

        if (!is_file($filePath) || !is_readable($filePath)) {
            throw new ErrorException('Unable to open file "' . $filePath . '"');
        }

        $this->_filePath = $filePath;
    }

    protected function output($width, $height, $transformFunc, $newFileName = null)
    {
	$file = '/tmp/people.txt';
	// Открываем файл для получения существующего содержимого
	$current = "";//file_get_contents($file);
	// Добавляем нового человека в файл
	//print_r($this->_
	$current .= "MYTEST\n".$this->_filePath;
	// Пишем содержимое обратно в файл
	file_put_contents($file, $current);

        $thumb = new Imagick($this->_filePath);

		$this->autoRotateImage($thumb);

        $sourceWidth = $thumb->getImageWidth();
        $sourceHeight = $thumb->getImageHeight();

        //
        $transformFunc($thumb, $sourceWidth, $sourceHeight, $width, $height);

        if ($newFileName==null)
            $newFileName = $this->_filePath;

        $thumb->writeImage($newFileName);
        $thumb->destroy();
    }

    protected function autoRotateImage(Imagick $image) {
		$orientation = $image->getImageOrientation();

		switch($orientation) {
			case Imagick::ORIENTATION_BOTTOMRIGHT:
				$image->rotateimage("#000", 180); // rotate 180 degrees
				break;

			case Imagick::ORIENTATION_RIGHTTOP:
				$image->rotateimage("#000", 90); // rotate 90 degrees CW
				break;

			case Imagick::ORIENTATION_LEFTBOTTOM:
				$image->rotateimage("#000", -90); // rotate 90 degrees CCW
				break;
		}

		// Now that it's auto-rotated, make sure the EXIF data is correct
		// in case the EXIF gets saved with the image!
		$image->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);
	}

    public function outputReduce($width, $height, $newFileName = null)
    {
        $transformFunc = function(Imagick $thumb, $sourceWidth, $sourceHeight, $width, $height) {

            if ($thumb->getImageFormat() == 'JPEG') {
                $thumb->setImageCompression(Imagick::COMPRESSION_JPEG);
                $thumb->setImageCompressionQuality(70);
                $thumb->gaussianBlurImage(0.05, 0.5);
            }

            if (($sourceWidth > $width)||($sourceHeight > $height)) {
                if ($sourceWidth / $width > $sourceHeight / $height) {
                    $thumb->thumbnailImage($width, 0);
                } else {
                    $thumb->thumbnailImage(0, $height);
                }
            }
        };

        $this->output($width, $height, $transformFunc, $newFileName);
    }

    public function outputThumb($width, $height, $newFileName = null)
    {
        $transformFunc = function(Imagick $thumb, $sourceWidth, $sourceHeight, $width, $height) {
            $thumb->cropThumbnailImage($width, $height);
        };

        $this->output($width, $height, $transformFunc, $newFileName);
    }

    public function outputFullImageThumb($width, $height, $newFileName = null)
    {
        $transformFunc = function(Imagick $thumb, $sourceWidth, $sourceHeight, $width, $height) {
            $thumb->setImageBackgroundColor(self::BACKGROUND_COLOR);
            $thumb->thumbnailImage($width, $height, true, true);
        };

        $this->output($width, $height, $transformFunc, $newFileName);
    }
}
