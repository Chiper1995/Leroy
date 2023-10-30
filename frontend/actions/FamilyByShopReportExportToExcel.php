<?php
namespace frontend\actions;

use common\components\actions\ListAction;
use common\components\ActiveRecord;
use common\components\User;
use common\models\interfaces\ISearchModel;
use common\rbac\Rights;
use PHPExcel;
use PHPExcel_Calculation_LookupRef;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Worksheet;
use ReflectionClass;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class FamilyByShopReportExportToExcel extends ListAction
{
    /* @var PHPExcel */
    private $objPHPExcel;

    /* @var PHPExcel_Worksheet */
    private $activeSheet;

    public function run()
    {
        $this->checkAccess(null);

        /** @var $searchModel ISearchModel */
        $searchModel = (new ReflectionClass($this->searchModelClass))->newInstance();
        $dataProvider = $searchModel->search($this->getModelClass(), Yii::$app->request->queryParams, $this->dataProviderConfig);

        self::setScenario($dataProvider->getModels(), $this->modelScenario);

        return $this->export($dataProvider);
    }

    private function export($dataProvider)
    {
        $this->objPHPExcel = new PHPExcel();
        $this->objPHPExcel->setActiveSheetIndex(0);
        $this->activeSheet = $this->objPHPExcel->getActiveSheet();

        // Заполняем свойства документа
        $this->setDocumentProperties();

        // Пишем данные на лист
        $this->writeData($dataProvider);

        // Выкидываем в поток
        $this->saveOutput();

        Yii::$app->end();

        return true;
    }

    /**
     * @param ActiveDataProvider $dataProvider
     */
    private function writeData($dataProvider)
    {
        /**@var User[] $families*/
        $families = $dataProvider->getModels();

        $columns = [
            'id', 'family_name', 'fio', 'username', 'email', 'phone', 'cities'
        ];
        if (!\Yii::$app->user->can(Rights::SHOW_ID_COLUMNS)) {
            unset($columns[0]);
        }

        /* @var ActiveRecord $model */
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();

        $rowIndex = 1;

        $cellIndex = 1;
        foreach ($columns as $column) {
            switch ($column) {
                case 'family_name': $label = 'Семья'; break;
                case 'cities': $label = 'Город'; break;
                default: $label = $model->getAttributeLabel($column);
            }
            $this->activeSheet->setCellValue($this->getCellAdr($rowIndex, $cellIndex), $label);
            $cellIndex++;
        }
        $rowIndex++;

        foreach ($families as $family) {
            $cellIndex = 1;
            foreach ($columns as $column) {
                switch ($column) {
                    case 'cities': $value = implode(", ", ArrayHelper::getColumn($family->{$column}, 'name')); break;
                    default: $value = $family->{$column};
                }
                $this->activeSheet->setCellValue($this->getCellAdr($rowIndex, $cellIndex++), $value);
            }
            $rowIndex++;
        }

        //region Set styles
        // шапка
        if ($rowIndex > 1) {
            $colsCount = $cellIndex - 1;
            $this->activeSheet->getStyle($this->getCellAdr(1, 1) . ':' . $this->getCellAdr(1, $colsCount))->getFont()->setBold(true);

            $allStyle = $this->activeSheet->getStyle($this->getCellAdr(1, 1) . ':' . $this->getCellAdr($rowIndex, $colsCount));
            $allStyle->getAlignment()->setWrapText(true);
            $allStyle->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

            for ($i = 1; $i <= $colsCount; $i++) {
                $this->activeSheet->getColumnDimensionByColumn($i)->setAutoSize(true);
            }
        }
        //endregion
    }

    private function setDocumentProperties()
    {
        $this->objPHPExcel->getProperties()
            ->setCreator("Families Leroy Merlin")
            ->setLastModifiedBy("Families Leroy Merlin")
            ->setTitle("Families Leroy Merlin Export")
            ->setSubject("Families Leroy Merlin Export")
            ->setDescription("Families Leroy Merlin Export")
            ->setKeywords("Families Leroy Merlin Export")
            ->setCategory("");
    }

    private function saveOutput()
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="FamilyByShopReport.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    private function getCellAdr($row, $col)
    {
        return PHPExcel_Calculation_LookupRef::CELL_ADDRESS($row, $col, 4, true);
    }
}