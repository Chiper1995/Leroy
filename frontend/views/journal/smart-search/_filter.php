<?php

use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\journal\AllJournalSmartSearch|frontend\models\journal\UserAllJournalSmartSearch */
?>

<?php $form = ActiveForm::begin([
    'id' => 'smartSearchFilterForm',
    'method' => 'GET',
    'action' => ['journal/smart-search'],
    'options' => ['data-pjax' => false],
    'enableAjaxValidation' => false,
    'validateOnSubmit' => false,
]); ?>
<div class="row">
    <div class="col-md-12 search-block">
        <?= $form->field($searchModel, 'smartSearch', [
            'template' => '{input}<button class="search-btn" type="submit"><i class="glyphicon glyphicon-search"></i></button>{error}'
        ])
            ->input('text', ['class' => 'input-lg form-control', 'placeholder' => $searchModel->getAttributeLabel('smartSearch'), 'required' => true])
            ->label(false); ?>
        <?= $form->field($searchModel, 'workRepair')
            ->hiddenInput(['value' => ''])
            ->label(false);
        ?>
        <?= $form->field($searchModel, 'type')
            ->hiddenInput(['value' => ''])
            ->label(false);
        ?>
        <?= $form->field($searchModel, 'roomRepair')
            ->hiddenInput(['value' => ''])
            ->label(false);
        ?>
        <?= $form->field($searchModel, 'city')
            ->hiddenInput(['value' => ''])
            ->label(false);
        ?>
        <?= $form->field($searchModel, 'typeFilter')
            ->hiddenInput(['value' => $searchModel->typeFilter])
            ->label(false);
        ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php $this->registerJs(<<<'JS'
    jQuery(document).ready(function () {
        $('#smartSearchFilterForm').on('submit', function(e){
         //   e.preventDefault();
		    $('#alljournalsmartsearch-workrepair, #useralljournalsmartsearch-workrepair').val(
		    	$('#work-repair-feed').val()
		    );
		    $('#alljournalsmartsearch-type, #useralljournalsmartsearch-type').val(
		    	$('#type-feed').val()
		    );
		    $('#alljournalsmartsearch-roomrepair, #useralljournalsmartsearch-roomrepair').val(
		    	$('#room-repair-feed').val()
		    );
		    $('#alljournalsmartsearch-city, #useralljournalsmartsearch-city').val(
		    	$('#city-feed').val()
		    );
		    
		    $('#all-journal-goods input[name="AllJournalSearch[goods_filter][]"],#all-journal-goods input[name="AllJournalSmartSearch[goods_filter][]"]')
                .clone(true)
                .attr('name', 'AllJournalSmartSearch[goods_filter][]')
                .hide()
                .appendTo('#smartSearchFilterForm');
		    
		    $('#repair-works-filter')
                .clone(true)
                .attr('name', 'AllJournalSmartSearch[repairWorks_filter][]')
                .hide()
                .appendTo('#smartSearchFilterForm');
	  	});
    });
JS
); ?>
