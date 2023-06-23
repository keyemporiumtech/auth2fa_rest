<?php
require_once ROOT . '/app/modules/util_pdf/plugin/fpdf/fpdf.php';
App::uses('PdfStructureModel', 'modules/util_pdf/classes');
App::uses('FPDFUtility', 'modules/util_pdf/utility');
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses('EnumTicketTemplate', 'modules/shop_warehouse/plugin/ticketPdfMaker/enums');
App::uses('TicketPdfConstant', 'modules/shop_warehouse/plugin/ticketPdfMaker/config');
App::uses('TicketGenericModel', 'modules/shop_warehouse/plugin/ticketPdfMaker/models');
App::uses('TicketInfoModel', 'modules/shop_warehouse/plugin/ticketPdfMaker/models');

class TicketFPDF extends FPDF {
    public $info;
    public $generic;
    // utils
    public $border = false;

    function __construct(PdfStructureModel $structure = null, TicketGenericModel $generic = null, TicketInfoModel $info = null) {
        $this->generic = $generic;
        $this->info = $info;
        if (empty($structure)) {
            $structure = new PdfStructureModel();
        }
        parent::FPDF($structure->orientation, $structure->unit, $structure->size);
    }

    // Page footer
    function Footer() {
        switch ($this->generic->template) {
        case EnumTicketTemplate::SIMPLE:
            $this->FooterSimple();
            break;
        }
    }

    // Page header
    function Header() {

    }

    // SIMPLE
    function FooterSimple() {
        $this->SetY(-15);
        $rowInfo = TranslatorUtility::__translate("PDF_PAGE", "pdf") . " " . $this->PageNo();
        $this->SetFont('Arial', 'I', 6);
        $this->Cell(0, 10, $rowInfo, 0, 0, 'C');
        $this->SetFont('Arial', '', 8);
        $this->Ln(5);
        $this->Cell(0, 10, TranslatorUtility::__translate("TICKET_FOOTER", TicketPdfConstant::$TRANSLATOR_FILE), 0, 0, 'C');
    }

}
