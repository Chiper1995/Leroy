<?php

use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use common\rbac\Rights;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Help */
/* @var $helpPages [] */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Справка', 'url' => ['view']];
$this->params['breadcrumbs'][] = ['label' => $model->title];

if (\Yii::$app->user->can(Rights::SHOW_HELP_PRESENTATION)) {
    $this->registerCssFile('@web/css/viewer.min.css');
    $this->registerJsFile('@web/js/pdf.worker.min.js');
    $this->registerJsFile('@web/js/pdf.min.js');
    $this->registerJsFile('@web/js/pdf_viewer.min.js');
    $this->registerJsFile('@web/js/pdfjs-viewer.min.js');

    $this->registerJs(
        <<<JS
    $(function() {
   
         $('.presentation').click(function(e) {
                e.preventDefault();
                let DEFAULT_URL = '/include/'+ $(this).data('pdf');
                PDFViewerApplication.close();
                 PDFViewerApplication.animationStartedPromise.then(function () {
                      PDFViewerApplication.open({
                        url: DEFAULT_URL,
                      });
                    });
                                  
                 $('#pdfModal')
                 .find('.modal-header > h4').text($(this).data('name')).end()
                 .find('footer a.download').attr('href', '/help/download?id='+$(this).data('pdf')).end()                   
                 .modal('show');
         })
         
         $('#pdfModal').on('show.bs.modal', function () {
            setTimeout(function(){
                PDFViewerApplication.pdfViewer._scrollUpdate();
            },500);
         });
     });
JS
        , yii\web\View::POS_END);
}
?>
    <div class="row">
        <div class="col-md-3" style="margin-bottom: 20px">
            <?php echo
            Nav::widget([
                'items' => $helpPages,
                'options' => ['class' => 'nav-pills nav-stacked'],
            ]);
            ?>
        </div>
        <div class="col-md-9">
            <h1><?php echo $this->title ?></h1>
            <div class="row">
                <div class="col-md-12">
                    <?php echo $model->content ?>
                    <?php
                    if (\Yii::$app->user->can(Rights::SHOW_HELP_PRESENTATION)) {
                        foreach ($model->presentations as $presentation) {?>
                            <p>
                            <?php
                            echo Html::a(
                                Html::tag('strong', Html::encode($presentation->title)),
                                '#',
                                [
                                    'class' => 'presentation',
                                    'data-pdf' => md5($presentation->file),
                                    'data-name' => str_replace(".pdf", "", $presentation->file)
                                ]); ?>
                            </p>
                            <?php
                            echo $presentation->content;
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
if (\Yii::$app->user->can(Rights::SHOW_HELP_PRESENTATION)) {
    echo $this->render('__modalPdf');
}
?>