<?php

namespace common\components;


use common\models\Journal;
use common\models\JournalGoods;
use PhpOffice\PhpWord\PhpWord;
use TCPDF;
use Yii;
use yii\base\Object;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\widgets\DetailView;
use \PhpOffice\PhpWord\Shared\Html as PhpWordHtml;

class TaskJournalToDOCXExporter extends Object
{
    public $task;
    public $dataProvider;
    private $docx;
    public $withImages; //флаг вклчения/отключения загрузки изображений

    public function init()
    {
        $this->docx = new \PhpOffice\PhpWord\PhpWord();
        $this->withImages = null;
        if (Yii::$app->request->get('withImages') !== null) {
            $this->withImages = Yii::$app->request->get('withImages');
        }
    }

    public static function export($task, $dataProvider)
    {
        $exporter = new TaskJournalToDOCXExporter(['task' => $task, 'dataProvider' => $dataProvider]);
        $exporter->doExport();
    }

    protected function doExport()
    {
        $this->prepareStyle();
        $this->fillProperties();
        $this->makeTitleSlide();
        $this->makeJournalRecordSlides();
        $this->outputFile();
    }

    //подготовка стиля
    private function prepareStyle()
    {
        $fontStyle12 = array('spaceAfter' => 60, 'size' => 12);
        $fontStyleName = 'rStyle';
        $this->docx->addFontStyle($fontStyleName, $fontStyle12);
        $paragraphStyleName = 'pStyle';
        $paragraphStyle = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 100);
        $this->docx->addParagraphStyle($paragraphStyleName, $paragraphStyle);

        $this->docx->addTitleStyle(null, array('size' => 22, 'bold' => true));
        $this->docx->addTitleStyle(1, array('size' => 20, 'color' => '333333', 'bold' => true));
        $this->docx->addTitleStyle(2, array('size' => 16, 'color' => '666666'));
        $this->docx->addTitleStyle(3, array('size' => 14, 'italic' => true));
        $this->docx->addTitleStyle(4, array('size' => 12, 'bold' => true));
    }

    //мета-данные документа
    private function fillProperties()
    {
        $properties = $this->docx->getDocInfo();
        $properties->setCreator('Семьи Леруа Мерлен');
        $properties->setLastModifiedBy('Семьи Леруа Мерлен');
        $properties->setCompany('Семьи Леруа Мерлен');
        $properties->setTitle('Отчет по задаче');
        $properties->setSubject($this->task->name);
        $properties->setKeywords('Семьи Леруа Мерлен');
        $properties->setDescription('Отчет по задаче: ' . $this->task->name);
    }

    //первая страница выгрузки с общей информацией
    private function makeTitleSlide()
    {
        $section = $this->docx->addSection();

        $section->addTitle('Задание: ' . $this->task->name, 1);
        $createdAt = \Yii::$app->formatter->asDateTime($this->task->created_at);
        $updatedAt = \Yii::$app->formatter->asDateTime($this->task->updated_at);
        $section->addTitle('Создано: ' . $createdAt, 4);
        $section->addTitle('Последние обновление: ' . $updatedAt, 4);
        $section->addTitle('Дедлайн: ' . $this->task->deadline, 4);
        $section->addTitle('Всего записей: ' . $this->dataProvider->totalCount, 4);
        $section->addTextBreak();
        $section->addTitle('Описание', 2);
        PhpWordHtml::addHtml($section, $this->task->description, false, false);
        $section->addTextBreak();
        $section->addTitle('Создал задачу: ' . $this->task->creator->fio, 4);

        $section->addPageBreak();
    }

    //перебор записей и заполнений доки
    private function makeJournalRecordSlides()
    {
        foreach ($this->dataProvider->getModels() as $model) {
            $journal = Journal::find()
                ->joinWith(['user'], true)
                ->with('photos')
                ->where(['{{%journal}}.id' => $model['id']])
                ->one();
            if ($journal->status == Journal::STATUS_PUBLISHED)
                $this->makeJournalRecordSlide($journal);
        }
    }

    //создание одной статьи в доке
    private function makeJournalRecordSlide($journal)
    {
        $section = $this->docx->addSection();

        $section->addTitle($journal->subject, 1);

        $section->addTitle('ID прользователя: ' . $journal->user->id, 4);
        $section->addTitle('ФИО пользователя: ' . $this->clean($journal->user->fio), 4);
        $section->addTitle('Семья: ' . $this->clean($journal->user->family_name), 4);

        if ($this->withImages) {
            $section->addTextBreak();
            $this->addPhotos($section, $journal);
        }

        $section->addTextBreak();
        $section->addText($this->clean($journal->content));
        $section->addTextBreak();
        $this->addGoods($this->docx, $journal);

        $section->addPageBreak();
    }

    //добавление фотографий
    private function addPhotos($section, $journal)
    {
        foreach ($journal->photos as $photo) {
            $photoPath = $photo->getPhotoThumb(1164, 760, true);
            if (file_exists($photoPath)) {
                $section->addImage(
                    $photoPath,
                    array(
                        'width' => 100,
                        'height' => 100,
                        'marginTop' => -1,
                        'marginLeft' => -1,
                        'wrappingStyle' => 'behind'
                    )
                );
            }
        }
    }

    //добавление товаров
    public function addGoods($docx, $journal)
    {
        if (count($journal->goods) > 0) {
            $section = $docx->addSection();
            $section->addTitle('Товары', 2);
            $total = 0;
            foreach ($journal->goods as $goods) {
                $total += $goods->quantity * $goods->price;

                $section->addTextBreak();
                $section->addTitle('Наименование товара: ' . $goods->goods->name, 4);
                $section->addText('Количество: ' . $goods->quantity);
                $section->addText('Цена, руб.: ' . Yii::$app->formatter->format(doubleval($goods->price), ['decimal', 2]));
                $section->addText('Сумма, руб: ' . Yii::$app->formatter->format(doubleval($goods->quantity * $goods->price), ['decimal', 2]));
                $section->addText('Где покупались: ' . (is_null($goods->goodsShop) ? '' : $goods->goodsShop->name));
            }

            $section->addTextBreak();
            $section->addTitle('Итого: ' . $total, 4);
        }
    }

    //создание доки и скачивание
    private function outputFile()
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $this->getFileName() . '"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        ob_clean();
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($this->docx, 'Word2007');
        $objWriter->save('php://output');
        die();
    }

    //название доки
    private function getFileName()
    {
        return 'task_journal_' . date("mdHis") . '.docx';
    }

    //очистить текст от html и прочего
    private function clean($value)
    {
        $value = strip_tags($value);
        $value = html_entity_decode($value);
        $value = urldecode($value);

        $search = array(
            "'&(quot|#34);'i",
            "'&(lt|#60);'i",
            "'&(gt|#62);'i",
            "'&(nbsp|#160);'i",
            "'&(iexcl|#161);'i",
            "'&(cent|#162);'i",
            "'&(pound|#163);'i",
            "'&(copy|#169);'i",
            "'&(hellip|#133);'i",
            "'&(amp|#38);'i",
            "/[^\w_ !?.,@\-+_=)(:;\"'*#~№]+/u",
        );


        $replace = array(
            "\"",
            "<",
            ">",
            " ",
            chr(161),
            chr(162),
            chr(163),
            chr(169),
            "...",
            " ",
            " ",
        );
        $value = preg_replace($search, $replace, $value);

        return $value;
    }

}
