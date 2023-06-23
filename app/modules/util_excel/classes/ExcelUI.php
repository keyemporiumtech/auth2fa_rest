<?php
App::uses("FileUtility", "modules/coreutils/utility");
// excel
App::uses("ExcelInfoModel", "modules/util_excel/classes");
App::uses("EnumExcelCellType", "modules/util_excel/config");
/**
 * @see https://github.com/PHPOffice/PhpSpreadsheet/blob/master/docs/index.md
 * @see https://phpspreadsheet.readthedocs.io/en/latest/topics/recipes/#styles
 */
require_once ROOT . '/app/modules/util_excel/plugin/phpspreadsheet/autoload.php';
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelUI {
    public $webrootPath = "";
    public $filename = "";
    public $completename = "";
    public $ext = "xlsx";
    public $excel = null;
    public $writer = null;

    function __construct($webroot, $name = null, $ext = null, $firstSheetTitle = null) {
        if (empty($name)) {
            $name = FileUtility::uuid();
        }
        $this->excel = new Spreadsheet();
        $this->webrootPath = $webroot;
        if (!empty($ext)) {
            $this->ext = $ext;
        }
        $this->filename = $name . "." . $this->ext;
        $this->completename = $this->webrootPath . $this->filename;

        if (!empty($firstSheetTitle)) {
            $this->renameActiveSheet($firstSheetTitle);
        }
    }

    function setExcelInfo(ExcelInfoModel $info) {
        if (!empty($info->title)) {
            $this->excel->getProperties()->SetTitle($info->title);
        }
        if (!empty($info->description)) {
            $this->excel->getProperties()->setDescription($info->description);
        }
        if (!empty($info->subject)) {
            $this->excel->getProperties()->setSubject($info->subject);
        }
        if (!empty($info->author)) {
            $this->excel->getProperties()->setCreator($info->author);
        }
        if (!empty($info->keywords)) {
            $this->excel->getProperties()->setKeywords($info->keywords);
        }
    }

    // SHEETS

    function renameActiveSheet($name) {
        $this->excel->getActiveSheet()->setTitle($name, false);
    }

    function addSheet($title, $position = null) {
        $myWorkSheet = new Worksheet($this->excel, $title);
        $this->excel->addSheet($myWorkSheet, $position);
        return $myWorkSheet;
    }

    /* --- cells ---*/
    function setValueFormula(Worksheet $sheet, $cell, $value) {
        $sheet->setCellValue($cell, $value);
        $sheet->getCell($cell)->getStyle()->setQuotePrefix(true);
    }
    function setValueDate(Worksheet $sheet, $cell, $value) {
        $excelDateValue = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($value);
        $sheet->setCellValue($cell, $excelDateValue);
        $sheet->getStyle($cell)->getNumberFormat()
            ->setFormatCode(
                \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DATETIME
            );
    }
    function setValueFormat(Worksheet $sheet, $cell, $value, $format) {
        $sheet->setCellValue($cell, $value);
        $sheet->getStyle($cell)->getNumberFormat()->setFormatCode($format);
    }
    function setValueExplicit(Worksheet $sheet, $cell, $value, DataType $type) {
        $sheet->setCellValueExplicit($cell, $value, $type);
    }
    function setValue(Worksheet $sheet, $cell, $value) {
        $sheet->setCellValue($cell, $value);
    }

    function writeValue(Worksheet $sheet, $cell, $value, EnumExcelCellType $type = null, DataType $dataType = null, $format = null) {
        switch ($type) {
        case EnumExcelCellType::FORMULA:
            $this->setValueFormula($sheet, $cell, $value);
            break;
        case EnumExcelCellType::DATE:
            $this->setValueDate($sheet, $cell, $value);
            break;
        case EnumExcelCellType::FORMAT:
            $this->setValueFormat($sheet, $cell, $value, $format);
            break;
        case EnumExcelCellType::EXPLICIT:
            $this->setValueExplicit($sheet, $cell, $value, $dataType);
            break;
        default:
            $this->setValue($sheet, $cell, $value);
            break;
        }

    }

    function writeValueByIndex(Worksheet $sheet, $row, $col, $value, EnumExcelCellType $type = null, DataType $dataType = null, $format = null) {
        $cell = $sheet->getCell([$col, $row])->getCoordinate();
        $this->writeValue($sheet, $cell, $value, $type, $dataType, $format);
    }
    /* --- cells ---*/

    // OPERATION
    function download() {
        if ($this->isReadyFile()) {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . urlencode($this->filename) . '"');
            $this->writer->save('php://output');
        }
    }
    function save($webroot = null, $name = null) {
        if ($this->isReadyFile()) {
            $out = WWW_ROOT . $this->completename;
            if (!empty($webroot)) {
                $out = WWW_ROOT . $webroot;
                if (!empty($name)) {
                    $out .= $name . "." . $this->ext;
                }
            } elseif (!empty($name)) {
                $out = WWW_ROOT . $this->webrootPath . $name . "." . $this->ext;
            }
            $this->writer->save($out);
        }
    }

    public function getContent() {
        if ($this->isReadyFile()) {
            $resource = WWW_ROOT . $this->completename;
            $this->writer->save($resource);
            if (file_exists($resource)) {
                $content = base64_encode(file_get_contents($resource));
                unlink($resource);
                return $content;
            }
        }
        return null;
    }

    function close() {
        $this->excel->disconnectWorksheets();
        unset($this->excel);
    }

    // utils
    private function isReadyFile() {
        $ready = false;
        if ($this->ext === "xlsx") {
            $this->writer = new Xlsx($this->excel);
            $ready = true;
        }
        if ($this->ext === "xls") {
            $this->writer = new Xls($this->excel);
            $ready = true;
        }
        return $ready;
    }
}
