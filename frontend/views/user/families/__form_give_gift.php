<?php

use common\models\User;
use frontend\models\user\FamilyGiveGiftForm;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model FamilyGiveGiftForm */
/* @var $familyPoints int */
/* @var $journalPoints string */

/** @var User $user */
$user = Yii::$app->user->identity;
?>

<?php Pjax::begin([
    'id' => 'give-gift-form-pjax',
]);?>
<?php $form = ActiveForm::begin([
    'action' => \yii\helpers\Url::to(['user/give-gift']),
    'id' => 'give-gift-form',
    'enableAjaxValidation' => false,
    'options' => ['data-pjax' => '0']
]); ?>
    <?php $label = \Yii::t('app', 'У вас {n, plural, one{# балл} few{# балла} many{# баллов} other{# баллов}} &ndash; сколько хотите подарить?', ['n' => $user->points]); ?>
    <?= $form->field($model, 'points')->textInput(['type' => 'number', 'min'=>'1', 'id'=>'give-gift-form-points'])->label($label) ?>
    <?= Html::activeHiddenInput($model, 'journal_id', ['id'=>'give-gift-form-journal-id']) ?>
    <?php if ($model->saved): ?>
        <?= Html::activeHiddenInput($model, 'saved', ['id'=>'give-gift-form-saved']) ?>
        <?= Html::hiddenInput('familyPoints', $familyPoints, ['id'=>'give-gift-form-family-points']) ?>
        <?= Html::hiddenInput('journalPoints', $journalPoints, ['id'=>'give-gift-form-journal-points']) ?>
    <?php endif; ?>

<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>