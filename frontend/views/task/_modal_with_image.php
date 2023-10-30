<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
?>

<?php $form = ActiveForm::begin([
    'id'=>'export-pdf-doc',
    'method'=>'GET',
    'action'=>[''],
    'options' => ['target'=>'_blank', 'data-pjax' => false],
    'enableAjaxValidation' => false,
    'validateOnSubmit'=>false,
]); ?>
    <input type="hidden" name="withImages" class="withImages" value="0" />
    <?= Html::input('hidden', 'id', $model->id) ?>
    <?= Html::input('hidden', 'ids', '', ['class' => 'ids-input']) ?>
<?php ActiveForm::end(); ?>

<?php $this->registerJs(<<<'JS'
    jQuery(document).ready(function () {
        $('body').on('click', '.export-pdf-doc', function () {
            var href = $(this).attr('href');
            $("#export-pdf-doc").attr('action', href.split('?')[0]);

            runExport($("#export-pdf-doc"), getUrlVar(href)['ids']);
            return false;
        });

        function getUrlVar(urlVar){
            var arrayVar = [];
            var valueAndKey = [];
            var resultArray = [];
            arrayVar = (urlVar.substr(1)).split('?');
            if(arrayVar[0]=="") return false;
            for (i = 0; i < arrayVar.length; i ++) {
                valueAndKey = arrayVar[i].split('=');
                resultArray[valueAndKey[0]] = valueAndKey[1];
            }
            return resultArray;
        }

        function runExport($form, ids) {
            bootbox.dialog({
                message: "Скачать записи дневников с картинками?",
                title: "Подтверждение",
                buttons: {
                    yes: {
                        label: "Да",
                        className: "btn-primary btn-with-margin-right",
                        callback: function () {
                            $form.find(".withImages").val(1);
                            if (ids) {
                                $form.find(".ids-input").val(ids);
                            }
                            $form.submit();
                        }
                    },
                    no: {
                        label: "Нет",
                        className: "btn-default",
                        callback: function () {
                            $form.find(".withImages").val(0);
                            if (ids) {
                                $form.find(".ids-input").val(ids);
                            }
                            $form.submit();
                        }
                    }
                }
            });
        }
    });
JS
);?>
