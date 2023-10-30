<?php
namespace common\components;


use common\models\Journal;
use common\models\JournalGoods;
use TCPDF;
use Yii;
use yii\base\Object;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\widgets\DetailView;

class TaskJournalToPDFExporter extends Object
{
    /**
     * @param \common\models\Task $task
     * @param ActiveDataProvider $dataProvider
     */
    public static function export($task, $dataProvider)
    {
        $exporter = new TaskJournalToPDFExporter(['task' => $task, 'dataProvider' => $dataProvider]);
        $exporter->doExport();
    }
    /**
     * @var \common\models\Task
     */
    public $task;

    /**
     * @var ActiveDataProvider
     */
    public $dataProvider;

    public $withImages; //флаг вклчения/отключения загрузки изображений

    /**
     * @var TCPDF
     */
    private $pdf;

    public function init()
    {
        $this->pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->withImages = null;
        if (Yii::$app->request->get('withImages') !== null) {
            $this->withImages = Yii::$app->request->get('withImages');
        }
    }

    protected function doExport()
    {
        $this->fillProperties();

        $this->makeTitleSlide();

        /**@var Journal $journal*/
        foreach ($this->dataProvider->getModels() as $journal) {
            if ($journal->status == Journal::STATUS_PUBLISHED)
                $this->makeJournalRecordSlides($journal);
        }
        $this->outputFile();
    }

    /**
     * Заполняем свойства документа
     */
    private function fillProperties()
    {
        $this->pdf->SetCreator('Семьи Леруа Мерлен');
        $this->pdf->SetAuthor('Семьи Леруа Мерлен');

        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);

        $this->pdf->SetMargins(20, 10, 20); // устанавливаем отступы (20 мм - слева, 25 мм - сверху, 25 мм - справа)
        $this->pdf->SetAutoPageBreak(true, 10);

        // Устанавливаем отступы между тегами
        $this->pdf->setHtmlVSpace([
            'p' => [
                0 => ['h' => 0.0001, 'n' => 1],
                1 => ['h' => 0.0001, 'n' => 1]
            ],

            'h1' => [
                0 => ['h' => 0.0001, 'n' => 1],
                1 => ['h' => 0.0001, 'n' => 1]
            ],

            'h2' => [
                0 => ['h' => 0.0001, 'n' => 1],
                1 => ['h' => 1, 'n' => 6]
            ],

            'ul' => [
                0 => ['h' => 0.0001, 'n' => 1],
                1 => ['h' => 0.0001, 'n' => 1]
            ],
        ]);

        // set font
        $this->pdf->SetFont('DejaVuSansCondensed', '', 12);
    }

    /**
     * Титульный слайд
     * @throws \Exception
     */
    private function makeTitleSlide()
    {
        $this->pdf->AddPage();

        // Заголовок
        $this->pdf->writeHTML(Html::tag('h1', 'Задание: ' . $this->task->name, ['style'=>'text-align: center; font-size: 18px;']), false, 0, false, 0);

        $html = DetailView::widget([
            'model' => $this->task,
            'attributes' => [
                [
                    'attribute' => 'created_at',
                    'format' =>  ['date', 'dd.MM.Y'],
                ],
                [
                    'attribute' => 'updated_at',
                    'format' =>  ['date', 'dd.MM.Y'],
                ],
                [   'attribute' => 'description',
                    'format' => 'raw',
                ],
                [
                    'label' => 'Создал',
                    'value' => call_user_func(function (\common\models\Task $data) {
                        if (!empty($data->creator->city->name)) {
                            return $data->creator->fio .' '. $data->creator->city->name;
                        } else {
                            return $data->creator->fio;
                        }
                    }, $this->task),
                    'format' => 'raw',
                ]
            ],
            'template' => '<tr><th>{label}:</th><td>{value}</td></tr>',
            'options' => ['cellpadding' => 6, 'border'=>0],
        ]);

        $html = '<style>th {font-weight: bold;}</style>'.$html;

        $this->pdf->writeHTMLCell(0, 0, 20, 50, $html);

        // reset pointer to the last page
        $this->pdf->lastPage();
    }

    /**
     * @param Journal $journal
     */
    private function makeJournalRecordSlides($journal)
    {
        $this->pdf->AddPage();
        $fio = '';
        $family_name = '';
        if ($journal->user->fio) {
            $fio = $journal->user->fio;
        }
        if ($journal->user->family_name) {
            $family_name = trim($journal->user->family_name);
        }
        // Заголовок
        $this->pdf->writeHTML(Html::tag('h1', $journal->subject, ['style'=>'text-align: center; font-size: 18px;']), false, 0, false, 0);
        $this->pdf->writeHTML(Html::tag('h4', 'ID Пользователя: ' . $journal->user->id, ['style'=>'text-align: center;']), false, 0, false, 0);
        $this->pdf->writeHTML(Html::tag('h4', 'Имя пользователя: ' . $fio, ['style'=>'text-align: center;']), false, 0, false, 0);
        $this->pdf->writeHTML(Html::tag('h4', 'Семья: ' . $family_name, ['style'=>'text-align: center;']), false, 0, false, 0);
        // Текст
        $content = strip_tags($journal->content, '<div>,<p>,<em>,<strong>,<br>,<b>,<ul>,<ol>,<a>,<li>');
        $this->pdf->writeHTML(Html::tag('div', $content, ['style'=>'font-size: 12px;']), false, 0, false, 0);

        // Фото
        if ($this->withImages) {
            foreach ($journal->photos as $photo) {
                $photoPath = $photo->getPhotoThumb(1164, 760, true);
                if (file_exists($photoPath)) {
                    $this->pdf->AddPage();
                    $this->pdf->Image($photoPath, 11, 15, 275);
                }
            }
        }

        // Товары
        if (count($journal->goods) > 0) {
            $html = '';
            $total = 0;
            /**@var JournalGoods $goods*/
            foreach ($journal->goods as $goods) {
                $total += $goods->quantity*$goods->price;

                $html .= '
                    <tr>
                        <td>'.$goods->goods->name.'</td>
                        <td>'.$goods->quantity.'</td>
                        <td>'.Yii::$app->formatter->format(doubleval($goods->price), ['decimal', 2]).'</td>
                        <td>'.Yii::$app->formatter->format(doubleval($goods->quantity*$goods->price), ['decimal', 2]).'</td>
                        <td>'.(is_null($goods->goodsShop) ? '' : $goods->goodsShop->name).'</td>
                    </tr>';
            }
            $html =
                '<style>th {border-bottom: solid 1px silver; font-weight: bold;} td {border-bottom: solid 1px silver}</style>'.
                '<h2 style="font-size: 16px;">Купленные товары</h2>'.
                '<table cellpadding="4">'.
                '<tr>
                    <th>Наименование</th>
                    <th>Количество</th>
                    <th>Цена, руб.</th>
                    <th>Сумма, руб.</th>
                    <th>Где покупались</th>
                </tr>'.
                $html.
                '<tr>
                    <th align="right" colspan="3">Итого:</th>
                    <th>'.Yii::$app->formatter->format(doubleval($total), ['decimal', 2]).'</th>
                    <th></th>
                </tr>'.
                '</table>';

            $this->pdf->AddPage();
            $this->pdf->writeHTML($html, false, 0, false, 0);
        }

        // reset pointer to the last page
        $this->pdf->lastPage();
    }

    /**
     * Выкидываем файл в поток
     */
    private function outputFile()
    {
        //Close and output PDF document
        $this->pdf->Output($this->getFileName().'.pdf', 'I');
        die();
    }

    /**
     * Формируем имя файла
     * @return string
     */
    private function getFileName()
    {
        return $this->task->id.'_'.date("mdHis");
    }
}
