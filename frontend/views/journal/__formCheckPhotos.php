<?php

use common\models\JournalCheckPhoto;
use common\widgets\EFineUploader\EFineUploader;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\Journal */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="row">
    <div class="col-md-12 col-sm-12 content-container" style="margin-bottom: 15px;">
        <div class="content-container-caption">
            <h2>Фотографии чеков</h2>
        </div>
        <div class="photo-uploader">
            <div class="drop-zone-container">
                <div class="drop-zone" id="journal-check-photo-uploader-drop-zone">
                    <div class="container-fluid">
                        <div class="row photos" id="journal-check-photos">
                            <?php foreach ($model->checkPhotos as $photoIndex => $photo):?>
                                <?php $photoUrl = JournalCheckPhoto::getUrlPath().'/'.$photo->photo; ?>
                                <div class="col-sm-3 col-md-3 photo photo-<?= $photoIndex ?>">
                                    <div class="thumbnail">
                                        <input name="Journal[checkPhotos][<?php echo $photoIndex ?>][photo]" value="<?php echo $photo->photo ?>" type="hidden" class="photo-input" />
                                        <input name="Journal[checkPhotos][<?php echo $photoIndex ?>][deleted]" value="0" type="hidden" class="photo-delete-input" />
                                        <?php if (!$photo->isNewRecord):?>
                                            <input name="Journal[checkPhotos][<?php echo $photoIndex ?>][id]" value="<?php echo $photo->id ?>" class="photo-id-input" type="hidden" />
                                        <?php endif;?>
                                        <a class="im" rel="gallery_journal-check-photos" href="<?php echo $photoUrl ?>">
                                            <img src="<?php echo $photo->getPhotoThumb(253, 190) ?>"/>
                                        </a>
                                        <div class="caption text-center">
                                            <a class="photo-delete" href="#"><i class="glyphicon glyphicon-trash"></i> Удалить</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                    <div class="drop-zone-text">Перетащи фото сюда для загрузки</div>
                </div>
            </div>

            <div class="control-group">
                <div class="controls">
                    <?php $this->registerJs('var journalCheckPhotoUploader;', View::POS_HEAD)?>
                    <?php $this->registerJs('journalCheckPhotoUploader = new PhotoUpload(\'journal-check-photos\', \'Journal[checkPhotos]\');')?>
                    <?= EFineUploader::widget([
                        'id' => 'JournalCheckPhotoUploader',
                        'cssClass' => 'photo-uploader-control',
                        'config' => [
                            'multiple' => true,
                            'autoUpload' => true,
                            'request' => [
                                'endpoint' => Url::to(['journal/upload-check-photo']),
                                'params' => array('_csrf' => Yii::$app->request->csrfToken),
                            ],
                            'retry' => ['enableAuto' => true, 'preventRetryResponseProperty' => true],
                            'chunking' => ['enable' => true, 'partSize' => 100],//bytes
                            'callbacks' => [
                                'onComplete' => new JsExpression('function(id, name, response){journalCheckPhotoUploader.onCompleteLoadImage(response.filename, response.fileurl, response.fullfileurl);}'),
                                'onValidateBatch' => new JsExpression("function(fileOrBlobData) {}"), // because of crash
                                //'onError'=>"js:function(id, name, errorReason){ }",
                            ],
                            'validation' => [
                                'allowedExtensions' => JournalCheckPhoto::getAllowedExtensions(),
                                'sizeLimit' => JournalCheckPhoto::getMaxSize(),
                                'minSizeLimit' => 0,// minimum file size in bytes
                            ],
                            'classes' => [
                                'button' => 'qq-upload-button'
                            ],

                            'dragAndDrop' => [
                                'hideDropzones' => false,
                                'disableDefaultDropzone' => true,
                                'extraDropzones' => [new JsExpression('document.getElementById("journal-check-photo-uploader-drop-zone")')],
                            ],
                            'text' => [
                                'uploadButton' => '<i class="glyphicon glyphicon-picture"></i> Добавить фото',
                            ],
                        ]
                    ]);
                    ?>
                    <?php \frontend\assets\MultiPhotoUploadAsset::register($this) ?>
                </div>
            </div>
        </div>
    </div>
</div>



<?= newerton\fancybox\FancyBox::widget([
    'target' => 'a[rel=gallery_journal-check-photos]',
    'helpers' => true,
    'mouse' => true,
    'config' => [
        'maxWidth' => '90%',
        'maxHeight' => '90%',
        'playSpeed' => 7000,
        'padding' => 0,
        'fitToView' => false,
        'width' => '70%',
        'height' => '70%',
        'autoSize' => false,
        'closeClick' => false,
        'openEffect' => 'elastic',
        'closeEffect' => 'elastic',
        'prevEffect' => 'elastic',
        'nextEffect' => 'elastic',
        'closeBtn' => false,
        'openOpacity' => true,
        'helpers' => [
            'title' => ['type' => 'float'],
            'buttons' => [],
            'thumbs' => ['width' => 68, 'height' => 50],
            'overlay' => [
                'locked' => false,
                'css' => [
                    'background' => 'rgba(0, 0, 0, 0.8)'
                ]
            ]
        ],
    ]
]);