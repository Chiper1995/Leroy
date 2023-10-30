<?php
/** Форма редактирования фотографии на странице профиля */
use common\models\User;
use common\widgets\EFineUploader\EFineUploader;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\User */
?>

<div class="photo-uploader family-photo-uploader">
    <div class="drop-zone-container">
        <div class="drop-zone" id="family-photo-uploader-drop-zone">
            <div class="container-fluid">
                <div class="row" id="family-photo">
                    <div class="photo-container">
                        <img src="<?php echo $model->getPhotoThumb(243, 182) ?>"/>
                        <?= \yii\bootstrap\Html::activeHiddenInput($model, 'photo');?>
                    </div>
                </div>
            </div>
            <div class="drop-zone-text">Перетащи фото сюда для загрузки</div>
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            <?php $this->registerJs('var familyPhotoUploader;', View::POS_HEAD)?>
            <?php $this->registerJs('familyPhotoUploader = new PhotoUpload(\'family-photo\', \'user-photo\');')?>
            <?= EFineUploader::widget([
                'id' => 'JournalPhotoUploader',
                'cssClass' => 'photo-uploader-control',
                'config' => [
                    'multiple' => false,
                    'autoUpload' => true,
                    'request' => [
                        'endpoint' => Url::to(['user/upload-photo']),
                        'params' => array('_csrf' => Yii::$app->request->csrfToken),
                    ],
                    'retry' => ['enableAuto' => true, 'preventRetryResponseProperty' => true],
                    'chunking' => ['enable' => true, 'partSize' => 100],//bytes
                    'callbacks' => [
                        'onComplete' => new JsExpression('function(id, name, response){familyPhotoUploader.onCompleteLoadImage(response.filename, response.fileurl, response.fullfileurl);}'),
                        'onValidateBatch' => new JsExpression("function(fileOrBlobData) {}"), // because of crash
                        //'onError'=>"js:function(id, name, errorReason){ }",
                    ],
                    'validation' => [
                        'allowedExtensions' => User::getAllowedExtensions(),
                        'sizeLimit' => User::getMaxSize(),
                        'minSizeLimit' => 0,// minimum file size in bytes
                    ],
                    'classes' => [
                        'button' => 'qq-upload-button'
                    ],

                    'dragAndDrop' => [
                        'hideDropzones' => false,
                        'disableDefaultDropzone' => true,
                        'extraDropzones' => [new JsExpression('document.getElementById("family-photo-uploader-drop-zone")')],
                    ],
                    'text' => [
                        'uploadButton' => '<i class="glyphicon glyphicon-picture"></i> Загрузить фото',
                    ],
                ]
            ]);
            ?>
            <?php $this->registerJsFile('@web/js/family-photo-upload.min.js', ['position' => View::POS_END, 'depends'=>\yii\web\JqueryAsset::className()])?>
        </div>
    </div>
</div>