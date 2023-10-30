<?php
namespace common\components\actions;

use common\components\Thumbnail;
use common\models\interfaces\IImageModel;
use common\widgets\EFineUploader\qqFileUploader;
use Yii;
use yii\base\ErrorException;
use yii\web\Response;

class UploadImageAction extends ModelAction
{
    public $thumbWidth = 243;

    public $thumbHeight = 182;

	/**
	 * @return array|string|Response
	 * @throws \ErrorException
	 * @throws \yii\base\Exception
	 */
    public function run()
    {
        if (!Yii::$app->request->isPost) {
            throw new ErrorException('Ошибка при загрузке изображения [1]');
        }

        /**@var IImageModel $imageModel*/
        $imageModel = $this->getModelClass();

        $tempFolder = $imageModel::getTempPath();

        if(!file_exists($tempFolder) && !is_dir($tempFolder)) mkdir($tempFolder, 0777, true);
        if(!file_exists($tempFolder.'/chunks') && !is_dir($tempFolder.'/chunks')) mkdir($tempFolder.'/chunks', 0777, true);

        // Задаём имя файла
        $uploader = new qqFileUploader();
        $uploader->allowedExtensions = $imageModel::getAllowedExtensions();
        $uploader->sizeLimit = $imageModel::getMaxSize();
        $uploader->chunksFolder = $tempFolder.'/chunks';

        $uploader->fileName = Yii::$app->security->generateRandomString();

        $uploadPath = $this->getUploadPath($imageModel);
        $result = $uploader->handleUpload($uploadPath);

        $result = $this->afterUpload($uploader, $result);

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }

    /**
     * @param IImageModel $imageModel
     * @return string
     */
    protected function getUploadPath($imageModel)
    {
        return $imageModel::getTempPath();
    }

    /**
     * @param qqFileUploader $uploader
     * @param array $result
     * @return array
     */
    protected function afterUpload($uploader, $result)
    {
        /**@var IImageModel $imageModel*/
        $imageModel = $this->getModelClass();
        $tempFolder = $imageModel::getTempPath();

        /////////////////////////////////////////////////////////////////////////////////////
        $t = new Thumbnail($tempFolder.'/'.$uploader->getUploadName());
        // Уменьшаем размер
        $t->outputReduce($imageModel::getMaxWidth(), $imageModel::getMaxHeight());
        // Создаем миниатюру
        $t->outputThumb($this->thumbWidth, $this->thumbHeight, $tempFolder.'/'.'thumb_'.$uploader->getUploadName());
        /////////////////////////////////////////////////////////////////////////////////////

        $result['fileurl'] = $imageModel::getTempUrlPath().'/thumb_'.$uploader->getUploadName();
        $result['fullfileurl'] = $imageModel::getTempUrlPath().'/'.$uploader->getUploadName();
        $result['filename'] = $uploader->getUploadName();

        return $result;
    }
}