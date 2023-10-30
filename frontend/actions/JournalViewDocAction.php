<?php
/**
 * Created by PhpStorm.
 * User: arshatilov
 * Date: 02.07.2018
 * Time: 10:51
 */

namespace frontend\actions;

use common\models\Journal;
use common\rbac\Rights;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;
use ReflectionClass;


class JournalViewDocAction extends JournalAction
{
    /**
     * @var string view for action
     */
    public $view = '/../pdf/_viewPdf';

    /**
     * @var string scenario for models
     */
    public $modelScenario = 'view';

    /**
     * @var string|array url for return after success update
     */
    public $returnUrl = 'index';


    public $withImages; //флаг вклчения/отключения загрузки изображений
    public $displayFormPhotos; //проверка прав на просмотр изображений


    public function beforeRun()
    {
        $this->withImages = null;
        if (Yii::$app->request->get('withImages') !== null) {
            $this->withImages = Yii::$app->request->get('withImages');
        }

        return parent::beforeRun();
    }

    /**
     * @param $id
     * @return array|string|Response
     * @throws NotFoundHttpException
     */
    public function run($ids)
    {
        if (Yii::$app->request->post('cancel') !== null) {
            return $this->controller->redirect([$this->returnUrl]);
        }
        $ids = explode(';', $ids);
        /**@var Journal $model */
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if (($model = $model::findOne($ids[0])) === null) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }

        $model->setScenario($this->modelScenario);

        // Проверка доступа
        $this->checkAccess($model);

        $this->displayFormPhotos = Yii::$app->user->can(Rights::EDIT_MY_JOURNAL_PHOTO, ['journal' => $model]);

        $docx = $this->createDoc($model);

        $this->outputFile($docx, $ids);
    }

    private function createDoc($model)
    {
        $docx = new \PhpOffice\PhpWord\PhpWord();

        $fontStyle12 = array('spaceAfter' => 60, 'size' => 12);
        $docx->addFontStyle('rStyle', $fontStyle12);
        $paragraphStyle = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 100);
        $docx->addParagraphStyle('pStyle', $paragraphStyle);
        $docx->addTitleStyle(null, array('size' => 22, 'bold' => true));
        $docx->addTitleStyle(1, array('size' => 20, 'color' => '333333', 'bold' => true));
        $docx->addTitleStyle(2, array('size' => 16, 'color' => '666666'));
        $docx->addTitleStyle(3, array('size' => 14, 'italic' => true));
        $docx->addTitleStyle(4, array('size' => 12, 'bold' => true));

        $properties = $docx->getDocInfo();
        $properties->setCreator('Семьи Леруа Мерлен');
        $properties->setLastModifiedBy('Семьи Леруа Мерлен');
        $properties->setCompany('Семьи Леруа Мерлен');
        $properties->setTitle('Семьи Леруа Мерлен');

        $section = $docx->addSection();

        $section->addTitle($model->subject, 1);

        $section->addTitle('ID прользователя: ' . $model->user->id, 4);
        $section->addTitle('ФИО пользователя: ' . $model->user->fio, 4);
        $section->addTitle('Семья: ' . $model->user->family_name, 4);

        if ($this->withImages) {
            $section->addTextBreak();
            $this->addPhotos($section, $model);
        }

        $section->addTextBreak();

        $section->addText($this->clean($model->content));

        $section->addTextBreak();
        $this->addGoods($docx, $model);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($docx, 'Word2007');
        $objWriter->save('tasks_total.docx');

        return $docx;
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
    private function outputFile($docx, $ids)
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $this->getFileName($ids) . '"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        if (ob_get_length() > 0) ob_clean();
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($docx, 'Word2007');
        $objWriter->save('php://output');
        die();
    }

    //название доки
    private function getFileName($ids)
    {
        return 'journal' . $ids[0] . '_' . date("mdHis") . '.docx';
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
