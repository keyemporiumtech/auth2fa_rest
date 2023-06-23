<?php
App::uses("PdfUtility", "modules/util_pdf/utility");
App::uses('PdfStructureModel', 'modules/util_pdf/classes');
App::uses('AppGenericPdf', 'modules/util_pdf/classes');
// PLUGIN
App::uses('EnumCvTemplate', 'modules/work_cv/plugin/cvPdfMaker/enums');
App::uses('CvPdfConstant', 'modules/work_cv/plugin/cvPdfMaker/config');
App::uses('CvFPDF', 'modules/work_cv/plugin/cvPdfMaker/fpdf');
App::uses('CvGenericModel', 'modules/work_cv/plugin/cvPdfMaker/models');
App::uses('CvInfoModel', 'modules/work_cv/plugin/cvPdfMaker/models');
// makers
App::uses('CvPdfSimple', 'modules/work_cv/plugin/cvPdfMaker/makers');

class CvPdf extends AppGenericPdf {
    public $generic;

    function __construct($webroot = "", $name = null, PdfStructureModel $structure = null, CvGenericModel $generic = null, CvInfoModel $info = null) {
        $this->generic = $generic;
        $this->font = $this->generic->font;
        $this->space = $this->generic->space;
        parent::__construct();
        $this->pdf = PdfUtility::getInstance($webroot, $name, $structure, null, new CvFPDF($structure, $generic, $info));
    }

    // abstracts
    function getTranslatorFile() {
        return CvPdfConstant::$TRANSLATOR_FILE;
    }
    function getMaxCharsLn() {
        return CvPdfConstant::$MAX_CHARS_LN;
    }

    // builder
    function makeCv(CvInfoModel $info = null) {
        if (empty($info)) {
            $info = $this->info;
        }
        $maker = null;
        switch ($this->generic->template) {
        case EnumCvTemplate::SIMPLE:
            $maker = new CvPdfSimple($this);
            $maker->makeCv($info);
            break;
        }

    }

}
