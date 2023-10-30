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

class SmartSearchResultsToPDFExporter extends Object
{
	/**
	 * @param string $smartSearch
	 * @param ActiveDataProvider $dataProvider
	 * @param boolean $withPhotos
	 */
    public static function export($smartSearch, $dataProvider, $withPhotos)
    {
        $exporter = new SmartSearchResultsToPDFExporter(['smartSearch'=>$smartSearch, 'dataProvider'=>$dataProvider, 'withPhotos'=>$withPhotos]);
        $exporter->doExport();
    }

    /**
     * @var string
     */
    public $smartSearch;

	/**
     * @var boolean
     */
    public $withPhotos;

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

        foreach ($this->dataProvider->getModels() as $model) {
			$journal = Journal::find()
				->joinWith(['user'], true)
				->with('photos')
				->where(['{{%journal}}.id' => $model['id']])
				->one();

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
        $this->pdf->SetTitle('Результаты поиска: ' . $this->smartSearch);

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
        $this->pdf->writeHTML(Html::tag('h1','Результаты поиска: ' . $this->smartSearch, ['style'=>'text-align: center; font-size: 18px;']), false, 0, false, 0);
        $this->pdf->writeHTML(Html::tag('h2','Найдено записей: ' . $this->dataProvider->totalCount, ['style'=>'text-align: center; font-size: 14px;']), false, 0, false, 0);

		$html = '';

        $html = '<style>th {font-weight: bold;}</style>'.$html;

        $this->pdf->writeHTMLCell(0, 0, 120, 50, $html);

        // reset pointer to the last page
        $this->pdf->lastPage();
    }

	/**
	 * @param Journal $journal
	 * @throws \yii\base\InvalidConfigException
	 */
    private function makeJournalRecordSlides($journal)
    {
        $this->pdf->AddPage();

        // Заголовок
        $this->pdf->writeHTML(Html::tag('h1', $journal->subject, ['style'=>'text-align: center; font-size: 18px;']), false, 0, false, 0);
        $this->pdf->writeHTML(Html::tag('h2', $journal->user->fio . '&nbsp;&nbsp;&nbsp;' . Yii::$app->formatter->asDate($journal->updated_at), [
        	'style'=>'text-align: center; font-size: 14px;']), false, 0, false, 0);

        // Текст
        $this->pdf->writeHTML(Html::tag('div', $journal->content, ['style'=>'font-size: 12px;']), false, 0, false, 0);

        // Фото
		if ($this->withPhotos) {
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
        return 'smart_search_'.date("mdHis");
    }
}
