<?php

/* @var $this yii\web\View */

use yii\bootstrap\Html;
use yii\web\View;
use yii\widgets\Pjax;

$this->context->layout = "login";
\kartik\select2\Select2Asset::register($this);
$this->title = 'Регистрация сотрудников';
$this->params['breadcrumbs'][] = $this->title;
$apikey = \Yii::$app->params['apikey'];
$actionForm = $actionForm ? $actionForm : 'Place';
?>
<?php
$script = <<<JS
    $("body").on('focus', '.input__field', onInputFocus);
    $("body").on('blur', '.input__field', onInputBlur);

    function onInputFocus( ev ) {
        classie.add( ev.target.parentNode, 'input--filled' );
    }

    function onInputBlur( ev ) {
        if( ev.target.value.trim() === '' ) {
            classie.remove( ev.target.parentNode, 'input--filled' );
        }
    }
JS;
$this->registerJs($script, View::POS_READY);
?>
<div class="signup-block">
    <h1><?php echo Html::encode($this->title) ?></h1>
    <div class="site-login">
        <div class="row">
            <div class="col-md-12">
                <?php Pjax::begin(['id' => 'pjaxEmployee', 'enablePushState' => false]); ?>
                <?php echo $this->render('_form' . $actionForm, [
                    'model' => $model,
                ]) ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
