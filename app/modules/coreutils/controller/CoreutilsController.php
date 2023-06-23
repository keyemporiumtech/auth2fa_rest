<?php
App::uses('AppController', 'Controller');
App::uses("PageUtility", "modules/coreutils/utility");
App::uses("DateUtility", "modules/coreutils/utility");
App::uses("Defaults", "Config/system");
App::uses("TimezoneUtility", "modules/coreutils/utility");
App::uses("Test", "Model");
App::uses("FileUtility", "modules/coreutils/utility");

class CoreutilsController extends AppController {

	public function home() {
	}

	public function pageutility() {
		$this->set("appFolder", PageUtility::getAppNameFolder());
		$this->set("appPath", PageUtility::getPathApp());
		$this->set("current1", PageUtility::getCurrentUrl($this));
		$this->set("current2", PageUtility::getCurrentUrl());
		$this->set("complete", PageUtility::getCurrentUrlComplete());
	}

	public function dateutility() {
		$this->set("currentTime", DateUtility::getCurrentTime());
		$this->set("currentDate", DateUtility::getCurrentDate());
	}

	public function timezoneutility() {
		$this->set("timezoneDb", Defaults::get("timezone_db"));
		$this->set("timezoneSystem", Defaults::get("timezone"));
		
		$test= new Test();
		$result= $test->find('first');
		$DATA_DB= $result ['Test'] ['created'];
		$DATA_SYSTEM= date('Y-m-d H:i:s');
		$this->set("actualDb", $DATA_DB);
		$this->set("actualSystem", $DATA_SYSTEM);
		
		// append Timezone
		$dbTz1= TimezoneUtility::appendTimezone($DATA_DB);
		$sysTz1= TimezoneUtility::appendTimezone($DATA_SYSTEM);
		$dbTz2= TimezoneUtility::appendTimezoneSpecific($DATA_DB, Defaults::get("timezone_db"));
		$sysTz2= TimezoneUtility::appendTimezoneSpecific($DATA_SYSTEM, Defaults::get("timezone"));
		$this->set("dbTz1", $dbTz1);
		$this->set("sysTz1", $sysTz1);
		$this->set("dbTz2", $dbTz2);
		$this->set("sysTz2", $sysTz2);
		
		// conversione timezone
		$convertDbToSystem= TimezoneUtility::convertDateTimezoneToServer($DATA_DB, Defaults::get("timezone_db"));
		$convertDbToSystemNOP= TimezoneUtility::convertDateTimezoneToServer($DATA_DB, Defaults::get("timezone_db"), false);
		$this->set("convertDbToSystem", $convertDbToSystem);
		$this->set("convertDbToSystemNOP", $convertDbToSystemNOP);
		
		// ESEMPIO CON TIMEZONE DIVERSI
		$tz1= "America/Los_Angeles";
		$tz2= "Asia/Beirut";
		$dta1= TimezoneUtility::appendTimezoneSpecific($DATA_SYSTEM, $tz1);
		$dta2= TimezoneUtility::appendTimezoneSpecific($DATA_SYSTEM, $tz2);
		$this->set("tz1", $tz1);
		$this->set("tz2", $tz2);
		$this->set("dta1", $dta1);
		$this->set("dta2", $dta2);
		$convert1= TimezoneUtility::convertDateTimezoneToServer($dta1, $tz1);
		$convert2= TimezoneUtility::convertDateTimezoneToServer($dta2, $tz2);
		$convert1NOP= TimezoneUtility::convertDateTimezoneToServer($dta1, $tz1, false);
		$convert2NOP= TimezoneUtility::convertDateTimezoneToServer($dta2, $tz2, false);
		$this->set("convert1", $convert1);
		$this->set("convert2", $convert2);
		$this->set("convert1NOP", $convert1NOP);
		$this->set("convert2NOP", $convert2NOP);
		$convert1A= TimezoneUtility::convertDateTimezoneToTimezone($dta1, $tz1, $tz1);
		$convert2A= TimezoneUtility::convertDateTimezoneToTimezone($dta2, $tz2, $tz1);
		$convert1ANOP= TimezoneUtility::convertDateTimezoneToTimezone($dta1, $tz1, $tz1, false);
		$convert2ANOP= TimezoneUtility::convertDateTimezoneToTimezone($dta2, $tz2, $tz1, false);
		$this->set("convert1A", $convert1A);
		$this->set("convert2A", $convert2A);
		$this->set("convert1ANOP", $convert1ANOP);
		$this->set("convert2ANOP", $convert2ANOP);
		$convert1B= TimezoneUtility::convertDateTimezoneToTimezone($dta1, $tz1, $tz2);
		$convert2B= TimezoneUtility::convertDateTimezoneToTimezone($dta2, $tz2, $tz2);
		$convert1BNOP= TimezoneUtility::convertDateTimezoneToTimezone($dta1, $tz1, $tz2, false);
		$convert2BNOP= TimezoneUtility::convertDateTimezoneToTimezone($dta2, $tz2, $tz2, false);
		$this->set("convert1B", $convert1B);
		$this->set("convert2B", $convert2B);
		$this->set("convert1BNOP", $convert1BNOP);
		$this->set("convert2BNOP", $convert2BNOP);
	}

	public function fileutility() {
		$this->set("uuid", FileUtility::uuid());
		$this->set("uuid_medium", FileUtility::uuid_medium());
		$this->set("uuid_medium_unique", FileUtility::uuid_medium_unique());
		$this->set("uuid_short", FileUtility::uuid_short());
		$this->set("password", FileUtility::password());
	}

	/**
	 * Usata dalla suite di test
	 */
	public function testParams() {
		$this->set("request", $this->request);
	}

	public function fieldPhpPost() {
		$this->set("key1", PageUtility::getFieldByPhpForm("key1"));
	}

	public function fieldPhpGet() {
		$this->set("key1", PageUtility::getFieldByPhpForm("key1"));
	}
}