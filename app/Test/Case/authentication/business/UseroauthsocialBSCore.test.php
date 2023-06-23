<?php
App::uses("UseroauthsocialBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class UseroauthsocialBSCoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new UseroauthsocialBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "useroauthsocials");
			$bs= new UseroauthsocialBS();
			$obj= $bs->instance();
			$obj ['Useroauthsocial'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "useroauthsocials", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "useroauthsocials", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "useroauthsocials", "cod='mioCodTest'");
		}
	}

	function testUnique() {
		$autoIncrement= $this->addRecord();
		$bs= new UseroauthsocialBS();
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Useroauthsocial'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testAll() {
		$autoIncrement= $this->addRecord();
		$bs= new UseroauthsocialBS();
		$bs->addCondition("id", 1);
		$list= $bs->all();
		$this->assertEquals($list [0] ['Useroauthsocial'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "useroauthsocials");
		
		// obj
		$bs= new UseroauthsocialBS();
		$obj= $bs->instance();
		$obj ['Useroauthsocial'] ['cod']= "mioCodTest";		
		
		// save
		$bs= new UseroauthsocialBS();
		$id= $bs->save($obj);
		
		// search
		$search= "SELECT * FROM useroauthsocials WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['useroauthsocials'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "useroauthsocials", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "useroauthsocials", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "useroauthsocials", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new UseroauthsocialBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Useroauthsocial'] ['cod']= "OthermioCodTest";
		
		// edit
		$bs= new UseroauthsocialBS();
		$id= $bs->save($objNew);
		
		// test
		$bs= new UseroauthsocialBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Useroauthsocial'] ['cod'], 'OthermioCodTest');
		
		// reset
		$bs= new UseroauthsocialBS();
		$id= $bs->save($obj);
		
		// test
		$bs= new UseroauthsocialBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Useroauthsocial'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "useroauthsocials");
		
		// insert
		$sql= "INSERT INTO useroauthsocials (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM useroauthsocials WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['useroauthsocials'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$bs= new UseroauthsocialBS();
		$id= $bs->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "useroauthsocials", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "useroauthsocials", "cod='mioCodTest'");
	}
}