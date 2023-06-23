<?php
App::uses("PdfUtility", "modules/util_pdf/utility");
App::uses('PdfStructureModel', 'modules/util_pdf/classes');
// plugin
App::uses('EnumTicketTemplate', 'modules/shop_warehouse/plugin/ticketPdfMaker/enums');
App::uses('TicketPdf', 'modules/shop_warehouse/plugin/ticketPdfMaker');
App::uses('TicketGenericModel', 'modules/shop_warehouse/plugin/ticketPdfMaker/models');
App::uses('TicketInfoModel', 'modules/shop_warehouse/plugin/ticketPdfMaker/models');

class TicketPdfMaker {

    static function printTicket($webroot = "", $name = null, PdfStructureModel $structure = null, TicketGenericModel $generic = null, TicketInfoModel $info) {
        $plugin = TicketPdfMaker::makePlugin($webroot, $name, $structure, $generic, $info);
        $plugin->preview();
    }

    static function downloadTicket($webroot = "", $name = null, PdfStructureModel $structure = null, TicketGenericModel $generic = null, TicketInfoModel $info) {
        $plugin = TicketPdfMaker::makePlugin($webroot, $name, $structure, $generic, $info);
        $plugin->download();
    }

    static function makePlugin($webroot = "", $name = null, PdfStructureModel $structure = null, TicketGenericModel $generic = null, TicketInfoModel $info) {
        $plugin = new TicketPdf($webroot, $name, $structure, $generic);
        $plugin->makeTicket($info);
        return $plugin;
    }

    static function example() {

    }

}
