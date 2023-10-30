<?php
//phpinfo();


set_time_limit(3000);
ini_set('max_execution_time', 3000);

//define('YII_ENV', IS_DEV_SERVER ? 'dev' : 'prod');


defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

ini_set('display_errors', 'On');


defined('YII_APP_BASE_PATH') or define('YII_APP_BASE_PATH', dirname(dirname(__DIR__)));

require(YII_APP_BASE_PATH . '/vendor/autoload.php');
require(YII_APP_BASE_PATH . '/vendor/yiisoft/yii2/Yii.php');
require(YII_APP_BASE_PATH . '/common/config/bootstrap.php');

//$config = require(YII_APP_BASE_PATH . '/tests/config/acceptance.php');

//$config =require(YII_APP_BASE_PATH . '/common/config/environments/params-dev.php');

$config = require(YII_APP_BASE_PATH . '/frontend/config/main.php');
//echo '<pre>'.\yii\helpers\VarDumper::dumpAsString($config, 10, true).'</pre>'; die();

$application = new yii\web\Application($config);

$application->run(1);

echo "HELLO";


$user_id  = Yii::$app->user->identity->id;
echo "USER_ID=".$user_id;
//var_dump(Yii::$app->user->identity);


$sql="select email from bs_user where username like '%testuser%'";
$command=Yii::$app->db->createCommand($sql)->queryAll();
//print_r($command);
foreach($command as $commands)
  echo $commands['email']; 
//echo $command[0]['email']; //prints gametitle

die;

$html = <<<HTML
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<p>Simple Content</p>
</body>
</html>
HTML;

// create PDF file from HTML content :
Yii::$app->html2pdf
    ->convert($html)
    ->saveAs('/tmp/output.pdf');

// convert HTML file to PDF file :
Yii::$app->html2pdf
    ->convertFile('/tmp/source.html')
    ->saveAs('/tmp/output2.pdf');


die;

require_once dirname(__FILE__).'/../vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;


try {
    $html2pdf = new Html2Pdf('P', 'A4', 'fr');

    $content = file_get_contents(K_PATH_MAIN.'examples/data/utf8test.txt');
    $content = '<page style="font-family: freeserif"><br />'.nl2br($content).'</page>';

    $html2pdf->pdf->SetDisplayMode('real');
    $html2pdf->writeHTML($content);
    $html2pdf->output('utf8.pdf');
} catch (Html2PdfException $e) {
    $html2pdf->clean();

    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}



Yii::import('application.extensions.tcpdf.HTML2PDF');
try
{
    $html2pdf = new HTML2PDF('P', 'A4', 'en');
    //$html2pdf->setModeDebug();
    $html2pdf->setDefaultFont('Arial');
    $html2pdf->writeHTML($content,false);
    $html2pdf->Output("pdfdemo.pdf");        
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}

die;


# HTML2PDF has very similar syntax
        $html2pdf = Yii::$app->ePdf->HTML2PDF();
        $html2pdf->WriteHTML($this->renderPartial('index', array(), true));
        $html2pdf->Output();

        ////////////////////////////////////////////////////////////////////////////////////

        # Example from HTML2PDF wiki: Send PDF by email
        $content_PDF = $html2pdf->Output('', EYiiPdf::OUTPUT_TO_STRING);


/*


public function actionMpdfBlog($id) {
        $this->layout = 'pdf';
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        
        $model = $this->findModel($id);
        
        //$model = $this->findModel();
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
            'content' => $this->render('viewpdf', ['model'=>$model]),
            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.img-circle {border-radius: 50%;}', 
            'options' => [
                'title' => $model->title,
                'subject' => 'PDF'
            ],
            'methods' => [
                'SetHeader' => ['Школа'],
                'SetFooter' => ['|{PAGENO}|'],
            ]
        ]);
        return $pdf->render();
    }


php echo Html::a('<img class="left" width="30px" src="/images/pdf.png" /> Распечатать .PDF', ['/blog/mpdf-blog?id='.$model->id], [
                                'class'=>'btn btn-default',
                                'target'=>'_blank', 
                                'data-toggle'=>'tooltip', 
                                'title'=>'Will open the generated PDF file in a new window'
                            ]);
*/

//use common\rbac\Rights;
//YII::$app->user->can(Rights::EDIT_JOURNAL, ['journal'=>$model]);

//(new yii\web\Application($config))->run();

?>
