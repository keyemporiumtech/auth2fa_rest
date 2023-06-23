<?php
//utility
App::uses("HtmlUtility", "modules/coreutils/utility");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("StringUtility", "modules/coreutils/utility");
App::uses("MisureUtility", "modules/coreutils/utility");
App::uses("MathUtility", "modules/coreutils/utility");
// pdf
App::uses("EnumMisureArray", "modules/coreutils/config");
App::uses('PdfElement', 'modules/util_pdf/classes');
App::uses('PageNoFPDF', 'modules/util_pdf/classes');
App::uses('PageNoModel', 'modules/util_pdf/classes');
App::uses('PdfInfoModel', 'modules/util_pdf/classes');
App::uses('PdfStructureModel', 'modules/util_pdf/classes');
App::uses('FPDFUtility', 'modules/util_pdf/utility');
//delegate
// App::uses('BarcodeUI', 'Model/AppPlugin/Barcode');
// App::uses('QrcodeUI', 'Model/AppPlugin/Qrcode');

class PdfUI {
    public $webrootPath = "";
    public $filename = "";
    public $completename = "";
    public $pdfElement = null;
    public $pdf = null;
    // objects
    public $page_number = null; // PageNoModel

    /**
     * __construct
     *
     * @param  string $webroot percorso a partire dalla cartella webroot (ex. img/tmp/.../ deve terminare con backslash)
     * @param  string $name nome del file senza l'estensione. Se vuoto viene generato
     * @param  mixed $structure struttura del file (orientamento, dimensione e unità di misura)
     * @param  mixed $page_no modello per la scrittura del numero di pagine (font, colore ...)
     * @param  mixed $fpdf oggetto della classe FPDF
     * @return void
     */
    function __construct($webroot, $name = null, PdfStructureModel $structure = null, PageNoModel $page_no = null, $fpdf = null) {
        if (empty($name)) {
            $name = FileUtility::uuid();
        }
        if (empty($structure)) {
            $structure = new PdfStructureModel();
        }
        $this->page_number = $page_no;
        if (!empty($page_no) && empty($fpdf)) {
            $fpdf = new PageNoFPDF($structure, $page_no);
        }
        $this->webrootPath = $webroot;
        $this->filename = $name . ".pdf";
        $this->completename = $this->webrootPath . $this->filename;
        $this->setPdfElement(new PdfElement($structure->orientation, $structure->unit, $structure->size, $fpdf));
    }
    //init
    function setPdfElement($pdfElement) {
        $this->pdfElement = $pdfElement;
        $this->setPdf($pdfElement->FPDF);
    }

    function setPdf(FPDF $pdf) {
        $this->pdf = $pdf;
    }

    function setPdfInfo(PdfInfoModel $info, $isUTF8 = false) {
        if (!empty($info->title)) {
            $this->pdf->SetTitle($info->title, $isUTF8);
        }
        if (!empty($info->subject)) {
            $this->pdf->SetSubject($info->subject, $isUTF8);
        }
        if (!empty($info->author)) {
            $this->pdf->SetAuthor($info->author, $isUTF8);
        }
        if (!empty($info->keywords)) {
            $this->pdf->SetKeywords($info->keywords, $isUTF8);
        }
    }

    //settings pre-write
    function position($x = null, $y = null) {
        FPDFUtility::position($this->pdf, $x, $y);
    }

    function x() {
        return $this->pdf->GetX();
    }
    function y() {
        return $this->pdf->GetY();
    }

    function text($text_color = "#000", $text_size = "12", $text_font = "Arial", $text_type = "") {
        FPDFUtility::text($this->pdf, $text_color, $text_size, $text_font, $text_type);
    }

    //writing
    function writeBarred($txt, $top, $left, $w, $h, $align = "L", $border = false, $background = null) {
        FPDFUtility::writeBarred($this->pdf, $txt, $top, $left, $w, $h, $align, $border, $background);
    }
    function write($txt, $top, $left, $w, $h, $align = "L", $border = false, $background = null) {
        FPDFUtility::write($this->pdf, $txt, $top, $left, $w, $h, $align, $border, $background);
    }

    function space($top) {
        $this->pdf->Ln($top);
    }

    function line($top, $background = null, $h = 0.2, $w = 190) {
        FPDFUtility::line($this->pdf, $top, $background, $h, $w);
    }

    function writeImage($webrootUrl, $x, $y, $w, $h, $flgpixel = false) {
        FPDFUtility::writeImage($this->pdf, $webrootUrl, $x, $y, $w, $h, $flgpixel);
    }

    function writePageNumber($align = "C", $position = "top", $height = 5, $page_tot = true) {
        if ($position == "bottom") {
            $height = 21;
            $this->pdf->SetY($this->pdfElement->size_height - $height);
            // $this->pdf->SetY(-21);
        }
        if ($position == "top") {
            $this->pdf->SetY($height);
        }
        $this->text("#000", 8);
        if ($page_tot) {
            $this->pdf->Cell(0, 0, 'Page ' . $this->pdf->PageNo() . '/{nb}', 0, 0, $align);
        } else {
            $this->pdf->Cell(0, 0, 'Page ' . $this->pdf->PageNo(), 0, 0, $align);
        }
    }

    function newPage() {
        $this->pdf->AddPage();
        $this->pdf->AliasNbPages();
    }

    function writeMultipleLines($text, $initX, $w, $h, $maxLenght, $border = 0, $ln = 0, $align = 'L') {
        FPDFUtility::writeMultipleLines($this->pdf, $text, $initX, $w, $h, $maxLenght, $border, $ln, $align);
    }

    //OPERATION
    public function download() {
        $this->pdf->Output();
    }

    public function save($webroot = null, $name = null) {
        $out = WWW_ROOT . $this->completename;
        if (!empty($webroot)) {
            $out = WWW_ROOT . $webroot;
            if (!empty($name)) {
                $out .= $name . ".pdf";
            }
        } elseif (!empty($name)) {
            $out = WWW_ROOT . $this->webrootPath . $name . ".pdf";
        }
        $this->pdf->Output($out);
    }

    public function preview() {
        $this->pdf->Output();
        ob_end_flush();
    }

    public function getContent() {
        $resource = WWW_ROOT . $this->completename;
        $this->pdf->Output($resource);
        if (file_exists($resource)) {
            $content = base64_encode(file_get_contents($resource));
            unlink($resource);
            return $content;
        }
    }

}
