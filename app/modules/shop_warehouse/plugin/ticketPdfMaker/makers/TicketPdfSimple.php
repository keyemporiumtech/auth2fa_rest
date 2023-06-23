<?php
// plugin
App::uses('TicketPdf', 'modules/shop_warehouse/plugin/ticketPdfMaker');
App::uses('TicketInfoModel', 'modules/shop_warehouse/plugin/ticketPdfMaker/models');
// models

class TicketPdfSimple {

    /**
     * PARENT
     *
     * @var TicketPdf
     */
    public $PARENT;

    function __construct(TicketPdf $parent) {
        $this->PARENT = $parent;
    }

    function makeTicket(TicketInfoModel $info) {
        $this->PARENT->pdf->writeImage("img" . DS . "tickets" . DS . $this->PARENT->generic->templateUrl(), 15, 10, 600, 330, true);
        if (!empty($this->PARENT->generic->fillColor)) {
            $this->PARENT->pdf->write("", 12, 37, 116, 66, 'L', true, $this->PARENT->generic->fillColor);
        }

        // organizator
        $this->PARENT->pdf->position(5, 10);
        $this->PARENT->pdf->text($this->PARENT->generic->color, "12", $this->PARENT->font, "I");
        $this->PARENT->pdf->write($info->organizator, 5, 25, 140, 5, "C", $this->PARENT->border);

        $this->PARENT->pdf->space($this->PARENT->space);

        // title
        $this->PARENT->pdf->text($this->PARENT->generic->color, "16", $this->PARENT->font, "B");
        $this->PARENT->write($info->title, 5, 38, 110, 5, "C", $this->PARENT->border);

        $this->PARENT->pdf->space($this->PARENT->space);

        // day and place
        $this->PARENT->pdf->text($this->PARENT->generic->color, "14", $this->PARENT->font, "B");
        $this->PARENT->pdf->write($this->PARENT->translate("TICKET_EVENT_DATE", array($info->dta)), 5, 38, 100, 10, "L", $this->PARENT->border);
        $this->PARENT->pdf->write($this->PARENT->translate("TICKET_EVENT_HOUR", array($info->hh)), 10, 38, 100, 10, "L", $this->PARENT->border);
        $this->PARENT->write($this->PARENT->translate("TICKET_EVENT_PLACE", array($info->place)), 10, 38, 100, 5, "L", $this->PARENT->border);

        // price
        $this->PARENT->pdf->text($this->PARENT->generic->color, "12", $this->PARENT->font, "I");
        $this->PARENT->pdf->position(15, 75);
        if (!empty($info->destinator)) {
            $this->PARENT->pdf->write($this->PARENT->translate("TICKET_EVENT_DESTINATOR", array($info->destinator)), 15, 25, 70, 5, "L", $this->PARENT->border);
            $this->PARENT->pdf->write($this->PARENT->translate("TICKET_EVENT_PRICE", array($info->price)), 0, 95, 70, 5, "R", $this->PARENT->border);
        } else {
            $this->PARENT->pdf->write($this->PARENT->translate("TICKET_EVENT_PRICE", array($info->price)), 15, 95, 70, 5, "R", $this->PARENT->border);
        }

        // code
        PdfUtility::writeBarcode($this->PARENT->pdf, 26, 85, 60, 5, $info->code, null, "barcode_" . $info->code, true, 90);
        PdfUtility::writeBarcode($this->PARENT->pdf, 155, 85, 60, 5, $info->code, null, "barcode_" . $info->code, true, 90);
        $this->PARENT->addCleanFile(WWW_ROOT . "tmp/barcode_{$info->code}.png");
    }

}