<?php
App::uses('AppController', 'Controller');
App::uses("ExcelUtility", "modules/util_excel/utility");
App::uses("EnumExcelCellType", "modules/util_excel/config");

class UtilexcelController extends AppController {

    public function home() {
        /*
    if (file_exists(WWW_ROOT . "tmp/provaPdfQrcode.png")) {
    unlink(WWW_ROOT . "tmp/provaPdfQrcode.png");
    }
    if (file_exists(WWW_ROOT . "tmp/provaPdfBarcode.png")) {
    unlink(WWW_ROOT . "tmp/provaPdfBarcode.png");
    }
    $pdf = PdfUtility::getInstance();
    $pdf->text("#000", "8", "Arial");
    $pdf->write("Testo nero dimensione 8 font Arial", 21, 111, 80, 5, "L");
    $pdf->text("#eb4034", "10", "Times");
    $pdf->write("Testo rosso dimensione 10 font Times", 5, 111, 80, 5, "L");
    $pdf->text("#343deb", "12", "Forte");
    $pdf->write("Testo blue dimensione 12 font Forte", 5, 111, 80, 5, "L");

    $this->set("obj", PdfUtility::getAttachmentByPdf($pdf));
     */
    }

    public function createExcel() {
        $excel = ExcelUtility::getInstance();
        $sheet1 = $excel->addSheet("Prova");
        $excel->writeValueByIndex($sheet1, 1, 1, "TITOLO");
        $excel->writeValueByIndex($sheet1, 2, 1, "Prova titolo");
        $excel->writeValueByIndex($sheet1, 1, 2, "NUMERO");
        $excel->writeValueByIndex($sheet1, 2, 2, "01234");
        $excel->download();
    }

}