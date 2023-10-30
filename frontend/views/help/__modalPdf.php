<?php

use yii\web\View;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
?>
<?php Modal::begin([
    'id' => 'pdfModal',
    'size' => 'modal-lg',
    'header' => '<h4></h4>'
]); ?>
<div id="container_viewer">
    <div id="viewerContainer">
        <div id="viewer" class="pdfViewer"></div>
    </div>

    <div id="loadingBar">
        <div class="progress"></div>
        <div class="glimmer"></div>
    </div>

    <div id="errorWrapper" hidden="true">
        <div id="errorMessageLeft">
            <span id="errorMessage"></span>
            <button id="errorShowMore">
                More Information
            </button>
            <button id="errorShowLess">
                Less Information
            </button>
        </div>
        <div id="errorMessageRight">
            <button id="errorClose">
                Close
            </button>
        </div>
        <div class="clearBoth"></div>
        <textarea id="errorMoreInfo" hidden="true" readonly="readonly"></textarea>
    </div>

    <footer>
        <button class="toolbarButton pageUp" title="Previous Page" id="previous"></button>
        <button class="toolbarButton pageDown" title="Next Page" id="next"></button>

        <input type="number" id="pageNumber" class="toolbarField pageNumber" value="1" size="4" min="1">

        <button class="toolbarButton zoomOut" title="Zoom Out" id="zoomOut"></button>
        <button class="toolbarButton zoomIn" title="Zoom In" id="zoomIn"></button>
        <a href="http://mail.ru" class="download"><button class="toolbarButton download" title="Download" id="download"></button></a>
    </footer>
</div>
<?php Modal::end();?>



