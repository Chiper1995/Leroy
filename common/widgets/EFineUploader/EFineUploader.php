<?php

namespace common\widgets\EFineUploader;

/**
 * EFineUploader class file.
 * This extension is a wrapper of https://github.com/Widen/fine-uploader
 *
 * @author Vladimir Papaev <kosenka@gmail.com>
 * @version 0.1
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
use yii\base\Exception;
use yii\base\Widget;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\web\View;

/**
 * How to use:
 *
 * view:
 * $this->widget('ext.EFineUploader.EFineUploader',
 * array(
 * 'id'=>'FineUploader',
 * 'config'=>array(
 * 'autoUpload'=>true,
 * 'request'=>array(
 * 'endpoint'=>'/files/upload',// OR $this->createUrl('files/upload'),
 * 'params'=>array('YII_CSRF_TOKEN'=>Yii::app()->request->csrfToken),
 * ),
 * 'retry'=>array('enableAuto'=>true,'preventRetryResponseProperty'=>true),
 * 'chunking'=>array('enable'=>true,'partSize'=>100),//bytes
 * 'callbacks'=>array(
 * 'onComplete'=>"js:function(id, name, response){  }",
 * //'onError'=>"js:function(id, name, errorReason){ }",
 * ),
 * 'validation'=>array(
 * 'allowedExtensions'=>array('jpg','jpeg'),
 * 'sizeLimit'=>2 * 1024 * 1024,//maximum file size in bytes
 * 'minSizeLimit'=>2*1024*1024,// minimum file size in bytes
 * ),
 * 'messages'=>array(
 * 'typeError'=>"Файл {file} имеет неверное расширение. Разрешены файлы только с расширениями: {extensions}.",
 * 'sizeError'=>"Размер файла {file} велик, максимальный размер {sizeLimit}.",
 * 'minSizeError'=>"Размер файла {file} мал, минимальный размер {minSizeLimit}.",
 * 'emptyError'=>"{file} is empty, please select files again without it.",
 * 'onLeave'=>"The files are being uploaded, if you leave now the upload will be cancelled."
 * ),
 * )
 * ));
 *
 * controller:
 *
 * public function actionUpload()
 * {
 * $tempFolder=Yii::getPathOfAlias('webroot').'/upload/temp/';
 *
 * mkdir($tempFolder, 0777, TRUE);
 * mkdir($tempFolder.'chunks', 0777, TRUE);
 *
 * Yii::import("ext.EFineUploader.qqFileUploader");
 *
 * $uploader = new qqFileUploader();
 * $uploader->allowedExtensions = array('jpg','jpeg');
 * $uploader->sizeLimit = 2 * 1024 * 1024;//maximum file size in bytes
 * $uploader->chunksFolder = $tempFolder.'chunks';
 *
 * $result = $uploader->handleUpload($tempFolder);
 * $result['filename'] = $uploader->getUploadName();
 * $result['folder'] = $webFolder;
 *
 * $uploadedFile=$tempFolder.$result['filename'];
 *
 * header("Content-Type: text/plain");
 * $result=htmlspecialchars(json_encode($result), ENT_NOQUOTES);
 * echo $result;
 * Yii::app()->end();
 * }

 */
class EFineUploader extends Widget
{
    public $id = "fineUploader";
    public $cssClass = '';
    public $config = array();
    public $css = null;

    public function run()
    {
        if (empty($this->config['request']['endpoint'])) {
            throw new Exception('EFineUploader: param "request::endpoint" cannot be empty.');
        }

        if (!is_array($this->config['validation']['allowedExtensions'])) {
            throw new Exception('EFineUploader: param "validation::allowedExtensions" must be an array.');
        }

        if (empty($this->config['validation']['sizeLimit'])) {
            throw new Exception('EFineUploader: param "validation::sizeLimit" cannot be empty.');
        }

        unset($this->config['element']);

        echo '<div id="' . $this->id . '"'.($this->cssClass != '' ? 'class="'.$this->cssClass.'"' : '').'><noscript><p>Please enable JavaScript to use file uploader.</p></noscript></div>';


        $view = $this->getView();

        EFineUploaderAsset::register($view);

        if (!empty($this->css))
            $view->registerCssFile($this->css);

        $config = array(
            'element' => new JsExpression('document.getElementById("' . $this->id . '")'),
            'debug' => false,
            'multiple' => false
        );
        $config = array_merge($config, $this->config);
        $config = Json::encode($config);

        $view->registerJs("var FineUploader_" . $this->id . " = new qq.FineUploader($config);", View::POS_LOAD, "FineUploader_" . $this->id);
    }
}