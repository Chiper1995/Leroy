<?php
namespace frontend\actions;

use common\components\actions\ListAction;
use common\models\Invite;
use PHPExcel;
use PHPExcel_Calculation_LookupRef;
use PHPExcel_Cell_DataType;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Worksheet;
use Yii;
use yii\helpers\ArrayHelper;

class InvitesExportToXlsxAction extends ListAction
{
    /* @var PHPExcel */
    private $objPHPExcel;

    /* @var PHPExcel_Worksheet */
    private $activeSheet;

    private $limit;
    private $offset;

	/**
	 * @return string|void
	 * @throws \PHPExcel_Exception
	 * @throws \yii\base\ExitException
	 */
    public function run()
    {
        $this->limit = Yii::$app->request->get('limit', null);
        $this->offset = Yii::$app->request->get('offset', null);

        $this->objPHPExcel = new PHPExcel();
        $this->objPHPExcel->setActiveSheetIndex(0);
        $this->activeSheet = $this->objPHPExcel->getActiveSheet();

        // Заполняем свойства документа
        $this->setDocumentProperties();

        // Пишем данные на лист
        $this->writeData();

        // Выкидываем в поток
        $this->saveOutput();

        Yii::$app->end();
    }

    private function writeData()
    {
        /**@var Invite[] $invites*/
        $invites = Invite::find()
            ->limit($this->limit)
            ->offset($this->offset)
			->where('COALESCE(phone, \'\') <> \'\' OR COALESCE(email, \'\') <> \'\'')
            ->all();

		$inviteExportAttributes = [
			'id', 'fio', 'phone', 'email', 'age', 'sex', 'city_id', 'city_other',
			'family', 'children', 'repair_status', 'repair_when_finish', 'typeOfRepair', 'repairObject',
			'have_cottage', 'plan_cottage_works', 'who_worker', 'who_chooser', 'who_buyer',
			'money', 'shop_name', 'distance', 'created_at'
		];

        $listsData = [
			'sex' => Invite::$L_SEX,
			'family' => Invite::$L_FAMILY,
			'children' => Invite::$L_HAVE_CHILDREN,
			'repair_status' => Invite::$L_REPAIR_STATUS,
			'repair_when_finish' => Invite::$L_REPAIR_WHEN_FINISH,
			'have_cottage' => Invite::$L_HAVE_COTTAGE,
			'plan_cottage_works' => Invite::$L_PLAN_COTTAGE_WORKS,
			'who_worker' => Invite::$L_WHO_WORKER,
			'who_chooser' => Invite::$L_WHO_CHOOSER,
			'who_buyer' => Invite::$L_WHO_BUYER,
			'money' => Invite::$L_MONEY,
			'distance' => Invite::$L_DISTANCE,
		];
        
        $rowIndex = 1;
        $cellIndex = 0;
        foreach ($invites as $invite) {
            $rowIndex++;
            $cellIndex = 0;

            //region inviteData
            foreach ($inviteExportAttributes as $attribute) {
                if ($rowIndex == 2) {
                    $this->activeSheet->setCellValueByColumnAndRow($cellIndex, $rowIndex - 1, $invite->getAttributeLabel($attribute));
                }

				if ($attribute === 'city_id') {
					$this->activeSheet->setCellValueByColumnAndRow($cellIndex, $rowIndex, $invite->city ? $invite->city->name : '');
				}
				else if ($attribute === 'typeOfRepair') {
					$this->activeSheet->setCellValueByColumnAndRow($cellIndex, $rowIndex, implode(";\n", $invite->$attribute));
					$this->activeSheet->getStyleByColumnAndRow($cellIndex, $rowIndex)->getAlignment()->setWrapText(true);
				}
				else if ($attribute === 'repairObject') {
					$this->activeSheet->setCellValueByColumnAndRow($cellIndex, $rowIndex, implode(";\n", $invite->getRepairObjectsText()));
					$this->activeSheet->getStyleByColumnAndRow($cellIndex, $rowIndex)->getAlignment()->setWrapText(true);
				}
				else if (in_array($attribute, array_keys($listsData))) {
					$this->activeSheet->setCellValueByColumnAndRow($cellIndex, $rowIndex, ArrayHelper::getValue($listsData[$attribute], $invite->$attribute));
				}
				else if ($attribute == 'created_at') {
					$this->activeSheet->setCellValueByColumnAndRow($cellIndex, $rowIndex, date('d.m.Y', $invite->$attribute));
				}
				else {
					$this->activeSheet->setCellValueByColumnAndRow($cellIndex, $rowIndex, (string)$invite->$attribute, true)
						->setDataType(PHPExcel_Cell_DataType::TYPE_STRING);
				}

				$cellIndex++;
            }
            //endregion
        }

        //region Set styles
        // шапка
        if ($rowIndex > 1) {
            $this->activeSheet->getStyle($this->getCellAdr(1, 1) . ':' . $this->getCellAdr(1, $cellIndex))->getFont()->setBold(true);

            $allStyle = $this->activeSheet->getStyle($this->getCellAdr(1, 1) . ':' . $this->getCellAdr($rowIndex, $cellIndex));
            $allStyle->getAlignment()->setWrapText(true);
            $allStyle->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

            for ($i = 1; $i <= $cellIndex; $i++) {
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
            ->setTitle("Families Leroy Merlin Export Invites")
            ->setSubject("Families Leroy Merlin Export Invites")
            ->setDescription("Families Leroy Merlin Export Invites")
            ->setKeywords("Families Leroy Merlin Export Invites")
            ->setCategory("Invites");
    }

    private function saveOutput()
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="invites.xlsx"');
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