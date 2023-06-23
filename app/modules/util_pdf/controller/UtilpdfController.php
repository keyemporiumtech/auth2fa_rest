<?php
App::uses('AppController', 'Controller');
App::uses("PdfUtility", "modules/util_pdf/utility");
App::uses('PageNoModel', 'modules/util_pdf/classes');

class UtilpdfController extends AppController {

    public function home() {
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
    }

    public function createPdf($border = false) {
        $pdf = PdfUtility::getInstance();
        parent::evalParamBool($border, "border", false);
        $pdf->text("#000", "8", "Arial");
        $pdf->write("Testo nero dimensione 8 font Arial", 21, 111, 80, 5, "L", $border);
        $pdf->text("#eb4034", "10", "Times");
        $pdf->write("Testo rosso dimensione 10 font Times", 5, 111, 80, 5, "L", $border);
        $pdf->text("#343deb", "12", "Forte");
        $pdf->write("Testo blue dimensione 12 font Forte", 5, 111, 80, 5, "L", $border);
        $pdf->text("#000", "8", "Tahoma");
        $pdf->write("Testo nero dimensione 8 font Tahoma", 5, 111, 80, 5, "L", $border);
        $pdf->text("#eb4034", "10", "Bernadette");
        $pdf->write("Testo rosso dimensione 10 font Bernadette", 5, 111, 80, 5, "L", $border);
        $pdf->text("#343deb", "12", "Avenir");
        $pdf->write("Testo blue dimensione 12 font Avenir", 5, 111, 80, 5, "L", $border);
        $pdf->preview();
    }

    public function createPdfNumbers($border = false, $position = null) {
        parent::evalParam($position, "position");
        $page_no = new PageNoModel();
        if ($position == "B") {
            $page_no->position = "bottom";
        }
        $pdf = PdfUtility::getInstance(null, null, null, $page_no);
        parent::evalParamBool($border, "border", false);
        $pdf->text("#000", "8", "Arial");
        $pdf->write("Testo nero dimensione 8 font Arial", 21, 111, 80, 5, "L", $border);
        $pdf->text("#eb4034", "10", "Times");
        $pdf->write("Testo rosso dimensione 10 font Times", 5, 111, 80, 5, "L", $border);
        $pdf->text("#343deb", "12", "Forte");
        $pdf->write("Testo blue dimensione 12 font Forte", 5, 111, 80, 5, "L", $border);

        $pdf->newPage();
        $pdf->text("#000", "8", "Tahoma");
        $pdf->write("Testo nero dimensione 8 font Tahoma", 21, 111, 80, 5, "L", $border);
        $pdf->text("#eb4034", "10", "Bernadette");
        $pdf->write("Testo rosso dimensione 10 font Bernadette", 5, 111, 80, 5, "L", $border);
        $pdf->text("#343deb", "12", "Avenir");
        $pdf->write("Testo blue dimensione 12 font Avenir", 5, 111, 80, 5, "L", $border);

        $pdf->preview();
    }

    public function createPrintcodes() {
        $pdf = PdfUtility::getInstance();
        $pdf->text("#000", "14", "Arial");
        $pdf->write("Esempio di qrcode", 21, 111, 80, 5, "L");

        PdfUtility::writeQrcode($pdf, 21, 35, 20, 20, "Prova", WWW_ROOT . "tmp/", "provaPdfQrcode", true);

        $pdf->text("#000", "14", "Arial");
        $pdf->write("Esempio di barcode", 15, 111, 80, 5, "L");

        PdfUtility::writeBarcode($pdf, 21, 85, 20, 20, "Prova", WWW_ROOT . "tmp/", "provaPdfBarcode", true);

        $pdf->preview();
    }
}