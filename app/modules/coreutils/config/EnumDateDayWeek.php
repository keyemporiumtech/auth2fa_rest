<?php

/**
 * Enumerations per i giorni (Mon,Tue,Wed,Thu,Fri,Sat,Sun)
 *
 * @author Giuseppe Sassone
 */
class EnumDateDayWeek {
    const LUNEDI = 'Mon';
    const MARTEDI = 'Tue';
    const MERCOLEDI = 'Wed';
    const GIOVEDI = 'Thu';
    const VENERDI = 'Fri';
    const SABATO = 'Sat';
    const DOMENICA = 'Sun';

    static $toNumber = array(
        EnumDateDayWeek::LUNEDI => 1,
        EnumDateDayWeek::MARTEDI => 2,
        EnumDateDayWeek::MERCOLEDI => 3,
        EnumDateDayWeek::GIOVEDI => 4,
        EnumDateDayWeek::VENERDI => 5,
        EnumDateDayWeek::SABATO => 6,
        EnumDateDayWeek::DOMENICA => 7,
    );
}
