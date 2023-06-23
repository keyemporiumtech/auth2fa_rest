<?php
require_once ROOT . '/app/modules/util_pdf/plugin/fpdf/fpdf.php';
App::uses('PdfStructureModel', 'modules/util_pdf/classes');
App::uses('FPDFUtility', 'modules/util_pdf/utility');
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses('EnumCvTemplate', 'modules/work_cv/plugin/cvPdfMaker/enums');
App::uses('CvPdfConstant', 'modules/work_cv/plugin/cvPdfMaker/config');
App::uses('CvGenericModel', 'modules/work_cv/plugin/cvPdfMaker/models');
App::uses('CvInfoModel', 'modules/work_cv/plugin/cvPdfMaker/models');

class CvFPDF extends FPDF {
    public $info;
    public $generic;
    // utils
    public $border = false;

    function __construct(PdfStructureModel $structure = null, CvGenericModel $generic = null, CvInfoModel $info = null) {
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
        case EnumCvTemplate::SIMPLE:
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
        $rowInfo = FPDFUtility::getTextPageNo($this);
        if (!empty($this->info->name)) {
            $rowInfo .= " - " . TranslatorUtility::__translate("CV_TITLE", CvPdfConstant::$TRANSLATOR_FILE) . " " . $this->info->name;
        }
        if (!empty($this->info->references())) {
            $rowInfo .= " - " . TranslatorUtility::__translate("CV_OTHER_INFOS", CvPdfConstant::$TRANSLATOR_FILE) . " " . $this->info->references();
        }
        $this->SetFont('Arial', 'I', 6);
        $this->Cell(0, 10, $rowInfo, 0, 0, 'C');
        $this->SetFont('Arial', '', 8);
        $this->Ln(5);
        $this->Cell(0, 10, TranslatorUtility::__translate("CV_FOOTER", CvPdfConstant::$TRANSLATOR_FILE), 0, 0, 'C');
    }
}
