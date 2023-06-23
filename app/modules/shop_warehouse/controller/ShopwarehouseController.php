<?php
App::uses('AppController', 'Controller');
App::uses('PriceUtility', 'modules/shop_warehouse/utility');
App::uses("EnumIVAType", "modules/shop_warehouse/config");
App::uses('EnumTicketTemplate', 'modules/shop_warehouse/plugin/ticketPdfMaker/enums');
App::uses('TicketGenericModel', 'modules/shop_warehouse/plugin/ticketPdfMaker/models');
App::uses('TicketInfoModel', 'modules/shop_warehouse/plugin/ticketPdfMaker/models');
App::uses('TicketPdfMaker', 'modules/shop_warehouse/plugin/ticketPdfMaker');
App::uses("TicketUtility", "modules/shop_warehouse/utility");
App::uses("EventBS", "modules/calendar/business");
App::uses('TicketRequestDto', 'modules/shop_warehouse/classes');
App::uses('TicketRequestInfoDto', 'modules/shop_warehouse/classes');
App::uses('TicketRequestDataDto', 'modules/shop_warehouse/classes');
App::uses('TicketRequestWeekDto', 'modules/shop_warehouse/classes');
App::uses('TicketRequestDataRangeDto', 'modules/shop_warehouse/classes');

class ShopwarehouseController extends AppController {

    public function home() {
    }

    public function priceUtility() {
        $total1 = 200;
        $iva_percent1 = 22;
        $this->set("total1", $total1);
        $this->set("iva_percent1", $iva_percent1);
        $this->set("calc1", PriceUtility::calcIva($total1, $iva_percent1, EnumIVAType::FREE));
        $this->set("calc2", PriceUtility::calcIva($total1, $iva_percent1, EnumIVAType::INCLUDED));
        $this->set("calc3", PriceUtility::calcIva($total1, $iva_percent1, EnumIVAType::EXCLUDED));

        $iva1 = 25;
        $this->set("iva1", $iva1);
        $this->set("calc4", PriceUtility::calcIvaPercent($total1, $iva1, EnumIVAType::FREE));
        $this->set("calc5", PriceUtility::calcIvaPercent($total1, $iva1, EnumIVAType::INCLUDED));
        $this->set("calc6", PriceUtility::calcIvaPercent($total1, $iva1, EnumIVAType::EXCLUDED));
    }

    public function createPdf($fillColor = null) {
        // TicketUtility::example();
        parent::evalParam($fillColor, "fillColor");

        $generic = new TicketGenericModel();

        $info = new TicketInfoModel();
        $info->organizator = "Organizzatore di eventi";
        $info->title = "Evento di prova";
        $info->dta = "20/11/2020";
        $info->hh = "18:30";
        $info->place = "Anfiteatro Centrale Piazza Roma num. 77";
        $info->destinator = "Giuseppe Sassone";
        $info->price = "22.5 EUR";
        $info->code = "A6T54UB88";

        TicketPdfMaker::printTicket("", null, null, $generic, $info);
    }

    public function ticketUtility() {

    }

    public function ticketRequestData($flgDtaTo = null) {
        parent::evalParam($flgDtaTo, "flgDtaTo");
        $event = array(
            "Event" => array(
                "id" => "1",
                "title" => "evento pubblico",
                "description" => "evento di prova per la generazione di ticket",
                "dtainit" => "2022-01-01 12:30:00",
                "dtaend" => null,
            ),
        );
        if (!empty($flgDtaTo)) {
            $event['Event']['dtaend'] = "2022-01-20 13:30:00";
        }
        $this->set("event", $event);

        // ---------- CASO 1
        $info1 = new TicketRequestInfoDto();
        $info1->price = 20.00;
        $info1->iva_percent = 10.00;
        $info1->currency = "EUR";
        $info1->quantity = 15;
        $info1->flg_ivainclude = EnumIVAType::EXCLUDED;

        $dates1 = array();
        $request1 = new TicketRequestDto();
        // richiesta data=dtainit hh <
        $requestData = new TicketRequestDataDto();
        $requestData->dta = "2022-01-01";
        $requestData->hh = "12:29";
        $requestData->info = $info1;
        array_push($dates1, $requestData);
        // richiesta data=dtainit hh =
        $requestData = new TicketRequestDataDto();
        $requestData->dta = "2022-01-01";
        $requestData->hh = "12:30";
        $requestData->info = $info1;
        array_push($dates1, $requestData);
        // richiesta data=dtainit hh >
        $requestData = new TicketRequestDataDto();
        $requestData->dta = "2022-01-01";
        $requestData->hh = "13:30";
        $requestData->info = $info1;
        array_push($dates1, $requestData);

        $request1->dates = $dates1;
        $this->set("request1", $request1);
        $tickets1 = TicketUtility::makeTicketsByEventObj($event, $request1, false);
        $this->set("tickets1", $tickets1);

        // ---------- CASO 2
        $info2 = new TicketRequestInfoDto();
        $info2->title = "Titolo assegnato dalla request";
        $info2->price = 31.50;
        $info2->currency = "EUR";

        $dates2 = array();
        $request2 = new TicketRequestDto();
        // richiesta data=dtaend hh <
        $requestData = new TicketRequestDataDto();
        $requestData->dta = "2022-01-20";
        $requestData->hh = "12:29";
        $requestData->info = $info2;
        array_push($dates2, $requestData);
        // richiesta data=dtaend hh =
        $requestData = new TicketRequestDataDto();
        $requestData->dta = "2022-01-20";
        $requestData->hh = "12:30";
        $requestData->info = $info2;
        array_push($dates2, $requestData);
        // richiesta data=dtaend hh >
        $requestData = new TicketRequestDataDto();
        $requestData->dta = "2022-01-20";
        $requestData->hh = "13:30";
        $requestData->info = $info2;
        array_push($dates2, $requestData);

        $request2->dates = $dates2;
        $this->set("request2", $request2);
        $tickets2 = TicketUtility::makeTicketsByEventObj($event, $request2, false);
        $this->set("tickets2", $tickets2);

    }

}