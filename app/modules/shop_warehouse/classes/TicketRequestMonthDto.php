<?php

class TicketRequestMonthDto {

    public $color;
    public $month; // EnumDateMonth
    public $days = array(); // number[]
    public $weeks = array(); // EnumDateDayWeek[]
    public $hh;
    public $info; // TicketRequestInfoDto
}