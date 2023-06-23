<?php

/**
 * Enumerations per i mesi (Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec)
 *
 * @author Giuseppe Sassone
 */
class EnumDateMonth {
    const GENNAIO = 'Jan';
    const FEBBRAIO = 'Feb';
    const MARZO = 'Mar';
    const APRILE = 'Apr';
    const MAGGIO = 'May';
    const GIUGNO = 'Jun';
    const LUGLIO = 'Jul';
    const AGOSTO = 'Aug';
    const SETTEMBRE = 'Sep';
    const OTTOBRE = 'Oct';
    const NOVEMBRE = 'Nov';
    const DICEMBRE = 'Dec';

    static $toNumber = array(
        EnumDateMonth::GENNAIO => 1,
        EnumDateMonth::FEBBRAIO => 2,
        EnumDateMonth::MARZO => 3,
        EnumDateMonth::APRILE => 4,
        EnumDateMonth::MAGGIO => 5,
        EnumDateMonth::GIUGNO => 6,
        EnumDateMonth::LUGLIO => 7,
        EnumDateMonth::AGOSTO => 8,
        EnumDateMonth::SETTEMBRE => 9,
        EnumDateMonth::OTTOBRE => 10,
        EnumDateMonth::NOVEMBRE => 11,
        EnumDateMonth::DICEMBRE => 12,
    );

}
