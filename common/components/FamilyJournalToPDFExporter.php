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

class FamilyJournalToPDFExporter extends Object
{
    /**
     * @param \common\models\User $family
     * @param ActiveDataProvider $dataProvider
     */
    public static function export($family, $dataProvider)
    {
        $exporter = new FamilyJournalToPDFExporter(['family'=>$family, 'dataProvider'=>$dataProvider]);
        $exporter->doExport();
    }

    /**
     * @var \common\models\User
     */
    public $family;

    /**
     * @var ActiveDataProvider
     */
    public $dataProvider;

    /**
     * @var TCPDF
     */
    private $pdf;

    public function init()
    {
        $this->pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    }

    protected function doExport()
    {
        $this->fillProperties();

        $this->makeTitleSlide();

        /**@var Journal $journal*/
        foreach ($this->dataProvider->getModels() as $journal) {
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
        $this->pdf->SetTitle('Дневник семьи: '.($this->family->family_name!=''?$this->family->family_name:$this->family->fio).'('.$this->family->username.')');

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
        $this->pdf->writeHTML(Html::tag('h1', $this->family->family_name.'<br/>'.$this->family->fio, ['style'=>'text-align: center; font-size: 18px;']), false, 0, false, 0);

        $html = DetailView::widget([
            'model' => $this->family,
            'attributes' => [
                [
                    'attribute' => 'created_at',
                    'format' =>  ['date', 'dd.MM.Y'],
                ],
                'username',
                'email',
                'phone',
                [
                    'label' => 'Город',
                    'attribute' => 'cities',
                    'value' => implode(', ', \yii\helpers\ArrayHelper::getColumn($this->family->cities, 'name')),
                    'format' => 'html'
                ],
                'address',
                [
                    'attribute' => 'totalSpent',
                    'value' =>  Yii::$app->formatter->format(doubleval($this->family->totalSpent), ['decimal', 2]).' руб.',
                ],
                'points',
            ],
            'template' => '<tr><th>{label}:</th><td>{value}</td></tr>',
            'options' => ['cellpadding' => 6, 'border'=>0],
        ]);

        $html = '<style>th {font-weight: bold;}</style>'.$html;

        $this->pdf->writeHTMLCell(0, 0, 120, 50, $html);

        $photoPath = $this->family->getPhotoThumb(166, 166, true);
        if (file_exists($photoPath)) {
            $this->pdf->Image($photoPath, 40, 53);
        }

        // reset pointer to the last page
        $this->pdf->lastPage();
    }

    /**
     * @param Journal $journal
     */
    private function makeJournalRecordSlides($journal)
    {
        $this->pdf->AddPage();

        // Заголовок
        $this->pdf->writeHTML(Html::tag('h1', $journal->subject, ['style'=>'text-align: center; font-size: 18px;']), false, 0, false, 0);

        // Текст
        $content = str_replace('<a', '<span', $journal->content);
        $content = str_replace('/a>', '/span>', $content);
        $this->pdf->writeHTML(Html::tag('div', $content, ['style'=>'font-size: 12px;']), false, 0, false, 0);

        // Фото
        foreach ($journal->photos as $photo) {
            $photoPath = $photo->getPhotoThumb(1164, 760, true);
            if (file_exists($photoPath)) {
                $this->pdf->AddPage();
                $this->pdf->Image($photoPath, 11, 15, 275);
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
        return $this->family->username.'_'.date("mdHis");
    }
}