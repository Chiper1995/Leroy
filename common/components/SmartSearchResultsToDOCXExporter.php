<?php
namespace common\components;


use common\models\Journal;
use common\models\JournalGoods;
use TCPDF;
use Yii;
use yii\base\Object;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use \PhpOffice\PhpWord\Shared\Html as PhpWordHtml;

class SmartSearchResultsToDOCXExporter extends Object
{
	/**
	 * @param string $smartSearch
	 * @param ActiveDataProvider $dataProvider
	 * @param boolean $withPhotos
	 */
    public static function export($smartSearch, $dataProvider, $withPhotos)
    {
        $exporter = new SmartSearchResultsToDOCXExporter(['smartSearch'=>$smartSearch, 'dataProvider'=>$dataProvider, 'withPhotos'=>$withPhotos]);
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
     * @var \PhpOffice\PhpWord\PhpWord
     */
    private $docx;

    public function init()
    {
        $this->docx = new \PhpOffice\PhpWord\PhpWord();
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
        /*$this->pdf->SetCreator('Семьи Леруа Мерлен');
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
        $this->pdf->SetFont('DejaVuSansCondensed', '', 12);*/

        $properties = $this->docx->getDocInfo();
		$properties->setCreator('Семьи Леруа Мерлен');
		$properties->setCompany('Семьи Леруа Мерлен');
		$properties->setTitle('Результаты поиска: ' . $this->smartSearch);
		$properties->setDescription('Результаты поиска: ' . $this->smartSearch);
		$properties->setLastModifiedBy('Семьи Леруа Мерлен');
		$properties->setSubject('Результаты поиска: ' . $this->smartSearch);
		$properties->setKeywords('Результаты поиска: ' . $this->smartSearch);
    }

    /**
     * Титульный слайд
     * @throws \Exception
     */
    private function makeTitleSlide()
    {
		$section = $this->docx->addSection(['breakType' => 'nextPage', 'orientation' => 'landscape',]);

		// Заголовок
		PhpWordHtml::addHtml($section, Html::tag('h1','Результаты поиска: ' . $this->smartSearch, ['style'=>'text-align: center; font-size: 18px;']));
		PhpWordHtml::addHtml($section, Html::tag('h2','Найдено записей: ' . $this->dataProvider->totalCount, ['style'=>'text-align: center; font-size: 14px;']));
    }

	/**
	 * @param Journal $journal
	 * @throws \yii\base\InvalidConfigException
	 */
    private function makeJournalRecordSlides($journal)
    {
		$section = $this->docx->addSection(['breakType' => 'nextPage', 'orientation' => 'landscape',]);

		// Заголовок
		PhpWordHtml::addHtml($section, Html::tag('h1', $journal->subject, ['style'=>'text-align: center; font-size: 18px;']));
		PhpWordHtml::addHtml($section, Html::tag('h2', $journal->user->fio . '&nbsp;&nbsp;&nbsp;' . Yii::$app->formatter->asDate($journal->updated_at), [
      		'style'=>'text-align: center; font-size: 14px;']));

		// Текст
		$content = \yii\helpers\HtmlPurifier::process($journal->content);
		PhpWordHtml::addHtml($section, Html::tag('div', $content, ['style'=>'font-size: 12px;']));

        // Фото
		if ($this->withPhotos) {
			foreach ($journal->photos as $photo) {
				$photoPath = $photo->getPhotoThumb(1164, 760, true);
				if (file_exists($photoPath)) {
					$section = $this->docx->addSection(['breakType' => 'nextPage', 'orientation' => 'landscape',]);
					$section->addImage(
						$photoPath,
						array(
							'width' => 700,
							'marginTop' => 15,
							'marginLeft' => 11,
							'wrappingStyle' => 'behind'
						)
					);
				}
			}
		}

        // Товары
        if (count($journal->goods) > 0) {
            $html = '';
            $total = 0;
            /**@var JournalGoods $goods*/
            foreach ($journal->goods as $goods) {
                $total += $goods->quantity * $goods->price;

                $html .= '
                    <tr>
                        <td style="border-bottom: solid 1px silver">'.$goods->goods->name.'</td>
                        <td style="border-bottom: solid 1px silver">'.$goods->quantity.'</td>
                        <td style="border-bottom: solid 1px silver">'.Yii::$app->formatter->format(doubleval($goods->price), ['decimal', 2]).'</td>
                        <td style="border-bottom: solid 1px silver">'.Yii::$app->formatter->format(doubleval($goods->quantity*$goods->price), ['decimal', 2]).'</td>
                        <td style="border-bottom: solid 1px silver">'.(is_null($goods->goodsShop) ? '' : $goods->goodsShop->name).'</td>
                    </tr>';
            }
            $html =
                '<h2 style="font-size: 16px;">Купленные товары</h2>'.
                '<table cellpadding="4" style="width: 100%">'.
                '<tr>
                    <th style="border-bottom: solid 1px silver; font-weight: bold;">Наименование</th>
                    <th style="border-bottom: solid 1px silver; font-weight: bold;">Количество</th>
                    <th style="border-bottom: solid 1px silver; font-weight: bold;">Цена, руб.</th>
                    <th style="border-bottom: solid 1px silver; font-weight: bold;">Сумма, руб.</th>
                    <th style="border-bottom: solid 1px silver; font-weight: bold;">Где покупались</th>
                </tr>'.
                $html.
                '<tr>
                    <th style="border-bottom: solid 1px silver; font-weight: bold;" align="right" colspan="3">Итого:</th>
                    <th style="border-bottom: solid 1px silver; font-weight: bold;">'.Yii::$app->formatter->format(doubleval($total), ['decimal', 2]).'</th>
                    <th></th>
                </tr>'.
                '</table>';

			$section = $this->docx->addSection(['breakType' => 'nextPage', 'orientation' => 'landscape',]);
			PhpWordHtml::addHtml($section, $html);
        }
    }

    /**
     * Выкидываем файл в поток
     */
    private function outputFile()
    {
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $this->getFileName() . '.docx' . '"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		ob_clean();
		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($this->docx, 'Word2007');
		$objWriter->save('php://output');
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
