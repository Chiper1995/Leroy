<?php

use common\models\JournalPhoto;
use common\widgets\EFineUploader\EFineUploader;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\Journal */
/* @var $photos JournalPhoto[]|null */
/* @var $form yii\bootstrap\ActiveForm */
if (!isset($photos))
    $photos = $model->photos;
?>

<div class="row">
    <div class="col-md-12 col-sm-12 content-container" style="margin-bottom: 15px;">
        <div class="content-container-caption<?php if ($model->hasErrors('photos')) echo ' has-error'; ?>">
            <h2>Фотографии</h2>
            <div class="buy-list-item buy-list-photo" style="display: none">
                <div class="buy-list-icon">
                    <div class="icon-photo" style=""></div>
                    <div class="text-content">
                        Прикладывайте к рассказам о покупках фото товаров/магазина, это завершает историю
                        и дополняет<br>предоставленную Вами информацию.
                    </div>
                </div>
            </div>
            <?= Html::error($model, 'photos', ['tag' => 'p', 'class' => 'help-block help-block-error']); ?>
        </div>
        <div class="photo-uploader">
            <div class="drop-zone-container">
                <div class="drop-zone" id="journal-photo-uploader-drop-zone">
                    <div class="container-fluid">
                        <div class="row photos flex-photo" id="journal-photos">
                            <?php foreach ($photos as $photoIndex => $photo):?>
                                <div class="col-sm-3 col-md-3 photo photo-<?= $photoIndex ?>">
                                    <div class="thumbnail" style="position: relative">
                                        <input name="Journal[photos][<?php echo $photoIndex ?>][photo]" value="<?php echo $photo->photo ?>" type="hidden" class="photo-input" />
                                        <input name="Journal[photos][<?php echo $photoIndex ?>][deleted]" value="0" type="hidden" class="photo-delete-input" />
                                        <input name="Journal[photos][<?php echo $photoIndex ?>][edit]" value="0" type="hidden" class="photo-edit-input" />
                                        <?php if (!$photo->isNewRecord):?>
                                            <input name="Journal[photos][<?php echo $photoIndex ?>][id]" value="<?php echo $photo->id ?>" class="photo-id-input" type="hidden" />
                                            <input name="Journal[photos][<?php echo $photoIndex ?>][description]" value="<?= $photo->description ?>" type="hidden" class="photo-description-input" />
                                        <?php endif;?>
                                        <a class="im" rel="gallery_journal-photos" href="<?php echo $photo->getPhotoUrl(); ?>">
                                            <img src="<?php echo $photo->getPhotoThumb(253, 190) ?>"/>
                                        </a>
                                        <div class="block caption text_review text-description-photo" data-text="<?=$photo->description?>">
                                                <div class="text card-body content-description" id="myShowBlock">
                                                    <div class="text-container">
                                                        <div class="text <?= (mb_strlen($photo->description) > 0) ? '' : 'hidden' ?> "><?= $photo->description ?></div>
                                                        <a class="cursor-pointer" onclick="toggleText(this)" data-label="Скрыть">Показать полностью</a>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="caption text-center">
                                            <a class="photo-edit" href="#" data-url="<?= Url::to(['journal-photo/update', 'id' => $photo->id]) ?>">
                                                <?php if (mb_strlen($photo->description) == 0):?>
                                                    <i class="glyphicon glyphicon-pencil"></i> <span class="edit-title">Добавить описание</span>
                                                <?php else:;?>
                                                    <i class="glyphicon glyphicon-pencil"></i> <span class="edit-title">Редактировать описание</span>
                                                <?php endif;?>
                                            </a>
                                        </div>

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
                    <?php $this->registerJs('var journalPhotoUploader;', View::POS_HEAD)?>
                    <?php $this->registerJs('journalPhotoUploader = new PhotoUpload(\'journal-photos\', \'Journal[photos]\');')?>
                    <?= EFineUploader::widget([
                        'id' => 'JournalPhotoUploader',
                        'cssClass' => 'photo-uploader-control',
                        'config' => [
                            'multiple' => true,
                            'autoUpload' => true,
                            'request' => [
                                'endpoint' => Url::to(['journal/upload-photo']),
                                'params' => array(
                                    '_csrf' => Yii::$app->request->csrfToken,
                                    'journalId' => $model->id,
                                ),
                            ],
                            'retry' => ['enableAuto' => true, 'preventRetryResponseProperty' => true],
                            'chunking' => ['enable' => true, 'partSize' => 100],//bytes
                            'callbacks' => [
                                'onComplete' => new JsExpression(
                                    'function(id, name, response){' .
                                    '   journalPhotoUploader.onCompleteLoadImage(response.filename, response.fileurl, response.fullfileurl, function(photoIndex, $thumbnailContainer){' .
                                    '      $thumbnailContainer.find(\'.photo-id-input\').val(response.id);' .
                                    '   },  response.id, response.description);' .
                                    '}'
                                ),
                                'onValidateBatch' => new JsExpression("function(fileOrBlobData) {}"), // because of crash
                                //'onError'=>"js:function(id, name, errorReason){ }",
                            ],
                            'validation' => [
                                'allowedExtensions' => JournalPhoto::getAllowedExtensions(),
                                'sizeLimit' => JournalPhoto::getMaxSize(),
                                'minSizeLimit' => 0,// minimum file size in bytes
                            ],
                            'classes' => [
                                'button' => 'qq-upload-button'
                            ],

                            'dragAndDrop' => [
                                'hideDropzones' => false,
                                'disableDefaultDropzone' => true,
                                'extraDropzones' => [new JsExpression('document.getElementById("journal-photo-uploader-drop-zone")')],
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

<script>
    function toggleText(element) {
        var container = element.closest('.text-container');
        var label = element.innerHTML;
        container.querySelector('.text').classList.toggle('fulltext');
        element.innerHTML = element.getAttribute('data-label');
        element.setAttribute('data-label', label);
    }
</script>

<?php $this->registerJs(<<<'JS'
    jQuery(function ($) {
        var textNode = $('.text-container .text');
        for (var i = 0; i < textNode.length; i++){
            if (textNode[i].clientHeight >= textNode[i].scrollHeight){
                $('.photo-' + i + ' .text-container .cursor-pointer').addClass('hidden');
            } else {
                $('.photo-' + i + ' .text-container .cursor-pointer').removeClass('hidden');
            }
        }
    });
JS
);?>

<?= newerton\fancybox\FancyBox::widget([
    'target' => 'a[rel=gallery_journal-photos]',
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