<?php

use \yii\helpers\HtmlPurifier;

$return_reason_array = explode("\r\n", $return_reason);
$return_reason_array[0] = 'Причина: ' . $return_reason_array[0];
$photo_return_reason_array = explode("\r\n", $return_photo_reason);
$photo_return_reason_array[0] = 'Причина: ' . $return_reason_array[0];
?>

<?php if(!empty($return_reason)):?>
<div class="row">
    <div class="alert alert-warning" role="alert">
        <p><b>Требуются уточнения</b></p>
        <?php foreach($return_reason_array as $text):?>
            <?php if(empty($text)) echo '<br />'; ?>
            <p style="word-wrap: break-word; text-indent: 1.5em;"><?= HtmlPurifier::process($text); ?></p>
        <?php endforeach;?>
    </div>
</div>
<?php endif;?>

<?php if(!empty($return_photo_reason) && $displayFormPhotos):?>
    <div class="row">
        <div class="alert alert-warning" role="alert">
            <p><b>Новые фотографии не приняты</b></p>
            <?php foreach($photo_return_reason_array as $text):?>
                <?php if(empty($text)) echo '<br />'; ?>
                <p style="word-wrap: break-word; text-indent: 1.5em;"><?= HtmlPurifier::process($text); ?></p>
            <?php endforeach;?>
        </div>
    </div>
<?php endif;?>
