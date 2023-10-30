<?php

use common\models\JournalPhoto;

/* @var $this yii\web\View */
/* @var $model common\models\Journal */
?>

<div class="row">
    <div class="col-md-12 col-sm-12 content-container" style="padding-left: 0; padding-bottom: 0">
        <div class="container-fluid">
            <div class="row">
                <?php foreach ($model->photos as $photoIndex => $photo):?>
                    <div class="col-sm-3 col-md-3 journal-photo journal-photo-<?= $photoIndex ?>">
                        <div class="thumbnail">
                            <input name="Journal[photos][<?php echo $photoIndex ?>][photo]" value="<?php echo $photo->photo ?>" type="hidden" class="journal-photo-input" />
                            <input name="Journal[photos][<?php echo $photoIndex ?>][deleted]" value="0" type="hidden" class="journal-photo-delete-input" />
                            <?php if (!$photo->isNewRecord):?>
                                <input name="Journal[photos][<?php echo $photoIndex ?>][id]" value="<?php echo $photo->id ?>" type="hidden" />
                            <?php endif;?>
                            <a class="im" rel="gallery" href="<?php echo $photo->getPhotoUrl(); ?>">
                                <img src="<?php echo $photo->getPhotoThumb(243, 182) ?>"/>
                            </a>
                        </div>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</div>

<?= newerton\fancybox\FancyBox::widget([
    'target' => 'a[rel=gallery]',
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
                'css' => [
                    'background' => 'rgba(0, 0, 0, 0.8)'
                ]
            ]
        ],
    ]
]);