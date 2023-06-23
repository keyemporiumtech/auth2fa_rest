<?php

class TicketRequestDto {

    public $dates = array(); // TicketRequestDataDto[]
    public $ranges = array(); // TicketRequestDataRangeDto[]
    public $weeks = array(); // TicketRequestWeekDto[]
    public $months = array(); // TicketRequestMonthDto[]
    public $template; // EnumTicketTemplate
}