<?php
App::uses("ConnectionManager", "Model");
App::uses("Defaults", "Config/system");

class MysqldbConnectionTest extends CakeTestCase {

    public function testRecordTabella() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $sql = "SELECT COUNT(*) as num FROM _tests";
        $data = $dbo->query($sql);
        $result = $data[0][0];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['num'] > 0, true);
    }

    public function testPrimaRiga() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $sql = "SELECT * FROM _tests";
        $data = $dbo->query($sql);
        $result = $data[0]['_tests'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'TEST001');
    }

    public function testInsertDeleteReset() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $maxid = "SELECT AUTO_INCREMENT FROM information_schema.TABLES";
        $maxid .= " WHERE TABLE_SCHEMA = '" . Defaults::get("db_name") . "' AND TABLE_NAME = '_tests'";
        $data = $dbo->query($maxid);
        $result = $data[0]['TABLES'];
        $autoIncrement = $result['AUTO_INCREMENT'];
        // clean
        $del = "DELETE FROM _tests WHERE cod='TEST002'";
        $data = $dbo->query($del);

        // insert
        $sql = "INSERT INTO _tests (id,cod,title,description,created) VALUES";
        $sql .= "(NULL, 'TEST002', 'SECONDO TEST', 'RIGA DA VERIFICARE CON PHPUnit', CURRENT_TIMESTAMP);";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM _tests WHERE cod='TEST002'";
        $data = $dbo->query($search);
        $result = $data[0]['_tests'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'TEST002');
        $this->assertEquals($result['title'], 'SECONDO TEST');

        // reset
        $del = "DELETE FROM _tests WHERE cod='TEST002'";
        $data = $dbo->query($del);
        $resetMaxid = "ALTER TABLE _tests AUTO_INCREMENT=" . ($autoIncrement - 1) . ";";
        $data = $dbo->query($resetMaxid);

        // verify reset
        $data = $dbo->query($maxid);
        $result = $data[0]['TABLES'];
        $this->assertEquals($result['AUTO_INCREMENT'], $autoIncrement);

        $sql = "SELECT COUNT(*) as num FROM _tests WHERE cod='TEST002'";
        $data = $dbo->query($sql);
        $result = $data[0][0];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['num'] == 0, true);
    }
}