<?php
App::uses("ConnectionManager", "Model");
App::uses("Defaults", "Config/system");

class MysqlUtilityTest {

	static function getAutoIncrement($dbo, $table) {
		$maxid= "SELECT AUTO_INCREMENT FROM information_schema.TABLES";
		$maxid.= " WHERE TABLE_SCHEMA = '" . Defaults::get("db_name") . "' AND TABLE_NAME = '{$table}'";
		$data= $dbo->query($maxid);
		$result= $data [0] ['TABLES'];
		return $result ['AUTO_INCREMENT'];
	}

	static function deleteLast($dbo, $table, $where) {
		$del= "DELETE FROM {$table} WHERE {$where}";
		$dbo->query($del);
	}

	static function resetAutoIncrement($dbo, $test, $table, $autoIncrement) {
		$resetMaxid= "ALTER TABLE {$table} AUTO_INCREMENT=" . ($autoIncrement - 1) . ";";
		$data= $dbo->query($resetMaxid);
		
		// verify reset
		$currentIncrement= MysqlUtilityTest::getAutoIncrement($dbo, $table);
		$test->assertEquals($currentIncrement, $autoIncrement);
	}

	static function verifyDeleted($dbo, $test, $table, $where) {
		$sql= "SELECT COUNT(*) as num FROM {$table} WHERE {$where}";
		$data= $dbo->query($sql);
		$result= $data [0] [0];
		$test->assertEquals(! empty($data), true);
		$test->assertEquals($result ['num'] == 0, true);
	}
}