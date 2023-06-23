<?php
require_once ROOT . '/app/modules/util_pdf/plugin/fpdf/fpdf.php';
App::uses('PageNoModel', 'modules/util_pdf/classes');
App::uses('PdfStructureModel', 'modules/util_pdf/classes');
App::uses('FPDFUtility', 'modules/util_pdf/utility');

class PageNoFPDF extends FPDF {
    public $page_no = null;

    function __construct(PdfStructureModel $structure, PageNoModel $page_no) {
        $this->page_no = $page_no;
        parent::__construct($structure->orientation, $structure->unit, $structure->size);
    }

    function Footer() {
        FPDFUtility::setPageNo($this->page_no, $this);
    }
}