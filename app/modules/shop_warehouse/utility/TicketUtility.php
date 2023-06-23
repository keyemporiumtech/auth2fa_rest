<?php
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("DateUtility", "modules/coreutils/utility");
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses("PdfUtility", "modules/util_pdf/utility");
App::uses('PdfStructureModel', 'modules/util_pdf/classes');
// app
App::uses('TicketObjDto', 'modules/shop_warehouse/classes');
App::uses('TicketRequestDto', 'modules/shop_warehouse/classes');
App::uses('TicketRequestInfoDto', 'modules/shop_warehouse/classes');
App::uses('TicketRequestDataDto', 'modules/shop_warehouse/classes');
App::uses('TicketRequestWeekDto', 'modules/shop_warehouse/classes');
App::uses('TicketRequestDataRangeDto', 'modules/shop_warehouse/classes');
App::uses("EventBS", "modules/calendar/business");
App::uses("TicketUI", "modules/shop_warehouse/delegate");
App::uses("TicketBS", "modules/shop_warehouse/business");
App::uses("PriceBS", "modules/shop_warehouse/business");
App::uses("PriceUtility", "modules/shop_warehouse/utility");
App::uses("EnumIVAType", "modules/shop_warehouse/config");
App::uses("CurrencyBS", "modules/util_currency/business");
App::uses("CurrencyUtility", "modules/util_currency/utility");
// plugin
App::uses('EnumTicketTemplate', 'modules/shop_warehouse/plugin/ticketPdfMaker/enums');
App::uses('TicketPdf', 'modules/shop_warehouse/plugin/ticketPdfMaker');
App::uses('TicketIntestModel', 'modules/shop_warehouse/plugin/ticketPdfMaker/models');
App::uses('TicketFPDF', 'modules/shop_warehouse/plugin/ticketPdfMaker/fpdf');

class TicketUtility {

    static function makeTicketsByEventObj($event = null, TicketRequestDto $request = null, $flgSave = true) {
        // build
        $objs = array(); // TicketObjDto
        $dtaFrom = $event['Event']['dtainit'];
        $dtaTo = $event['Event']['dtaend'];
        if (!ArrayUtility::isEmpty($request->dates)) {
            TicketUtility::checkDates($objs, $request->dates, $dtaFrom, $dtaTo);
        }
        if (!ArrayUtility::isEmpty($request->weeks)) {
            TicketUtility::checkWeeks($objs, $request->weeks, $dtaFrom, $dtaTo);
        }
        if (!ArrayUtility::isEmpty($request->ranges)) {
            TicketUtility::checkDataRanges($objs, $request->ranges, $dtaFrom, $dtaTo);
        }
        TicketUtility::fillInfoByEvent($objs, $event);

        return TicketUtility::makeTicketsByDtos($objs, $event['Event']['id'], $flgSave);
    }
    static function makeTicketsByEvent($id = null, $cod = null, TicketRequestDto $request = null, $flgSave = true) {
        $eventBS = new EventBS();
        if (!empty($cod)) {
            $eventBS->addCondition("cod", $cod);
        }
        $event = $eventBS->unique($id);

        // build
        $objs = array(); // TicketObjDto
        $dtaFrom = $event['Event']['dtainit'];
        $dtaTo = $event['Event']['dtaend'];
        if (!ArrayUtility::isEmpty($request->dates)) {
            TicketUtility::checkDates($objs, $request->dates, $dtaFrom, $dtaTo);
        }
        if (!ArrayUtility::isEmpty($request->weeks)) {
            TicketUtility::checkWeeks($objs, $request->weeks, $dtaFrom, $dtaTo);
        }
        if (!ArrayUtility::isEmpty($request->ranges)) {
            TicketUtility::checkDataRanges($objs, $request->ranges, $dtaFrom, $dtaTo);
        }
        TicketUtility::fillInfoByEvent($objs, $event);

        return TicketUtility::makeTicketsByDtos($objs, $event['Event']['id'], $flgSave);
    }

    static function makeTicketsByDtos($objs = array(), $id_event = null, $flgSave = true) {
        $tickets = array();
        foreach ($objs as $dto) {
            $price = null;
            if (!empty($dto->info->price)) {
                $priceBS = new PriceBS();
                $price = $priceBS->instance();
                if (!empty($dto->info->iva)) {
                    $priceRes = PriceUtility::calcIvaPercent($dto->info->price, $dto->info->iva, (!empty($dto->info->flg_ivainclude) ? $dto->info->flg_ivainclude : EnumIVAType::INCLUDED));
                    $price['Price']['total'] = $priceRes['total'];
                    $price['Price']['price'] = $priceRes['price'];
                    $price['Price']['iva'] = $priceRes['iva'];
                    $price['Price']['iva_percent'] = $priceRes['iva_percent'];
                } elseif (!empty($dto->info->iva_percent)) {
                    $priceRes = PriceUtility::calcIva($dto->info->price, $dto->info->iva_percent, (!empty($dto->info->flg_ivainclude) ? $dto->info->flg_ivainclude : EnumIVAType::INCLUDED));
                    $price['Price']['total'] = $priceRes['total'];
                    $price['Price']['price'] = $priceRes['price'];
                    $price['Price']['iva'] = $priceRes['iva'];
                    $price['Price']['iva_percent'] = $priceRes['iva_percent'];
                } else {
                    $price['Price']['price'] = !empty($dto->info->price) ? $dto->info->price : "0.00";
                    $price['Price']['total'] = $price['Price']['price'];
                }
                $currency = null;
                if (!empty($dto->info->currency)) {
                    $currencyBS = new CurrencyBS();
                    $currency = $currencyBS->get($dto->info->currency);
                } else {
                    $currency = CurrencyUtility::getCurrencySystem();
                }
                $price['Price']['currencyid'] = $currency['Currency']['id'];
            }

            $ticketBS = new TicketBS();
            $ticket = $ticketBS->instance();
            $ticket['Ticket']['name'] = $dto->info->title;
            $ticket['Ticket']['description'] = $dto->info->description;
            $ticket['Ticket']['quantity'] = !empty($dto->info->quantity) ? $dto->info->quantity : 1;
            $ticket['Ticket']['event'] = $id_event;
            $ticket['Ticket']['dtafrom'] = $dto->dta . (!empty($dto->hh) ? " " . $dto->hh . ":00" : "00:00:00");
            $ticket['Ticket']['flgwarehouse'] = 1;
            $ticket['Ticket']['flgreserve'] = 1;

            if ($flgSave) {
                $id_ticket = $ticketBS->save($ticket);
                $ticketUI = new TicketUI();
                $flgAddPrice = $ticketUI->addPrice($price, $id_ticket);
                if (!$flgAddPrice) {
                    $exception = $ticketUI->status ? new Exception($ticketUI->status->getExceptionMessage(), $ticketUI->status->getExceptionCod()) : new Exception(TranslatorUtility::__translate("ERROR_TICKET_EDIT", "ticket"));
                    throw $exception;
                }

                $ticketBS = new TicketBS();
                $ticketBS->addBelongsTo("price_fk");
                $ticket = $ticketBS->unique($id_ticket);
            } else {
                $ticket['Ticket']['price_fk'] = $price['Price'];
            }
            array_push($tickets, $ticket);
        }
        return $tickets;
    }

    static function fillInfoByEvent(&$objs = array(), $event) {
        for ($i = 0; $i < count($objs); $i++) {
            if (empty($objs[$i]->info)) {
                $objs[$i]->info = new TicketRequestInfoDto();
            }

            if (empty($objs[$i]->info->title)) {
                $objs[$i]->info->title = $event['Event']['title'];
            }

            if (empty($objs[$i]->info->description)) {
                $objs[$i]->info->description = $event['Event']['description'];
            }
        }
    }

    // dates
    static function checkDates(&$objs = array(), $dates = array(), $dtaFrom, $dtaTo = null) {
        $dtaCompare = null;
        foreach ($dates as $requestData) {
            $dtaCompare = $requestData->dta . (!empty($requestData->hh) ? " " . $requestData->hh . ":00" : "00:00:00");
            $checkValid = false;
            if (empty($dtaTo)
                && date('Y-m-d', strtotime($dtaFrom)) == date('Y-m-d', strtotime($dtaCompare))
                && date('H:i', strtotime($dtaFrom)) <= date('H:i', strtotime($dtaCompare))
            ) {
                // stesso giorno ma orario maggiore
                $checkValid = true;
            } elseif (DateUtility::beetwenDates($dtaCompare, $dtaFrom, $dtaTo)) {
                // compreso tra il range di date
                $checkValid = true;
            }

            if ($checkValid) {
                $obj = new TicketObjDto();
                $obj->dta = $requestData->dta;
                $obj->hh = $requestData->hh;
                $obj->color = $requestData->color;
                $obj->info = $requestData->info;
                array_push($objs, $obj);
            }
        }
    }

    static function checkWeeks(&$objs = array(), $weeks = array(), $dtaFrom, $dtaTo = null) {
        $dayFrom = date('D', strtotime($dtaFrom));

        if (empty($dtaTo)
            && ArrayUtility::containsObjectFieldByValue($weeks, $dayFrom, "week")
        ) {
            // il giorno dell'evento è compreso nei giorni della settimana
            $requestWeek = ArrayUtility::getObjectByFieldAndValue($weeks, $dayFrom, "week");
            if (!empty($requestWeek)) {
                $obj = new TicketObjDto();
                $obj->dta = date('Y-m-d', strtotime($dtaFrom));
                $obj->hh = date('H:i', strtotime($dtaFrom));
                $obj->color = $requestWeek->color;
                $obj->info = $requestWeek->info;
                array_push($objs, $obj);
            }

        } else {
            $dtaCurrent = $dtaFrom;
            $dayCurrent = null;
            while (strtotime($dtaCurrent) <= strtotime($dtaTo)) {
                $dayCurrent = date('D', strtotime($dtaCurrent));

                $requestWeek = ArrayUtility::getObjectByFieldAndValue($weeks, $dayCurrent, "week");
                if (!empty($requestWeek)) {
                    $obj = new TicketObjDto();
                    $obj->dta = date('Y-m-d', strtotime($dtaCurrent));
                    $obj->hh = date('H:i', strtotime($dtaCurrent));
                    $obj->color = $requestWeek->color;
                    $obj->info = $requestWeek->info;
                    array_push($objs, $obj);
                }

                $dtaCurrent = DateUtility::addToDate($dtaCurrent, 1, "d", "Y-m-d H:i:s");
            }
        }
    }

    static function checkDataRanges(&$objs = array(), $ranges = array(), $dtaFrom, $dtaTo = null) {
        if (empty($dtaTo)) {
            foreach ($ranges as $requestRange) {
                $rangeFrom = $requestRange->dtaFrom . (!empty($requestRange->hh) ? " " . $requestRange->hh . ":00" : "00:00:00");
                $rangeTo = $requestRange->dtaTo . (!empty($requestRange->hh) ? " " . $requestRange->hh . ":00" : "00:00:00");
                if (DateUtility::beetwenDates($dtaFrom, $rangeFrom, $rangeTo)) {
                    $obj = new TicketObjDto();
                    $obj->dta = date('Y-m-d', strtotime($dtaFrom));
                    $obj->hh = $requestRange->hh;
                    $obj->color = $requestRange->color;
                    $obj->info = $requestRange->info;
                    array_push($objs, $obj);
                }
            }
        } else {
            $dtaCurrent = $dtaFrom;

            while (strtotime($dtaCurrent) <= strtotime($dtaTo)) {

                foreach ($ranges as $requestRange) {
                    $rangeFrom = $requestRange->dtaFrom . (!empty($requestRange->hh) ? " " . $requestRange->hh . ":00" : "00:00:00");
                    $rangeTo = $requestRange->dtaTo . (!empty($requestRange->hh) ? " " . $requestRange->hh . ":00" : "00:00:00");
                    if (DateUtility::beetwenDates($dtaCurrent, $rangeFrom, $rangeTo)) {
                        $obj = new TicketObjDto();
                        $obj->dta = date('Y-m-d', strtotime($dtaCurrent));
                        $obj->hh = $requestRange->hh;
                        $obj->color = $requestRange->color;
                        $obj->info = $requestRange->info;
                        array_push($objs, $obj);
                    }
                }

                $dtaCurrent = DateUtility::addToDate($dtaCurrent, 1, "d", "Y-m-d H:i:s");
            }
        }
    }

}
