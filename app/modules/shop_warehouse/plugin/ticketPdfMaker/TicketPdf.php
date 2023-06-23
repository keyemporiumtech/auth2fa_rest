<?php
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses("PdfUtility", "modules/util_pdf/utility");
App::uses('EnumPdfFormat', 'modules/util_pdf/config');
App::uses('EnumPdfOrientation', 'modules/util_pdf/config');
App::uses('PdfStructureModel', 'modules/util_pdf/classes');
App::uses('AppGenericPdf', 'modules/util_pdf/classes');
// plugin
App::uses('EnumTicketTemplate', 'modules/shop_warehouse/plugin/ticketPdfMaker/enums');
App::uses('TicketPdfConstant', 'modules/shop_warehouse/plugin/ticketPdfMaker/config');
App::uses('TicketFPDF', 'modules/shop_warehouse/plugin/ticketPdfMaker/fpdf');
App::uses('TicketGenericModel', 'modules/shop_warehouse/plugin/ticketPdfMaker/models');
App::uses('TicketInfoModel', 'modules/shop_warehouse/plugin/ticketPdfMaker/models');
// makers
App::uses('TicketPdfSimple', 'modules/shop_warehouse/plugin/ticketPdfMaker/makers');

class TicketPdf extends AppGenericPdf {
    public $template;
    public $fillColor;

    function __construct($webroot = "", $name = null, PdfStructureModel $structure = null, TicketGenericModel $generic = null, TicketInfoModel $info = null) {
        $this->generic = $generic;
        $this->font = $this->generic->font;
        $this->space = $this->generic->space;
        parent::__construct();
        $this->pdf = PdfUtility::getInstance($webroot, $name, $structure, null, new TicketFPDF($structure, $generic, $info));
    }

    // abstracts
    function getTranslatorFile() {
        return TicketPdfConstant::$TRANSLATOR_FILE;
    }
    function getMaxCharsLn() {
        return TicketPdfConstant::$MAX_CHARS_LN;
    }

    // builder
    function makeTicket(TicketInfoModel $info) {
        if (empty($info)) {
            $info = $this->info;
        }
        $maker = null;
        switch ($this->generic->template) {
        case EnumTicketTemplate::SIMPLE:
            $maker = new TicketPdfSimple($this);
            $maker->makeTicket($info);
            break;
        }
    }

}
