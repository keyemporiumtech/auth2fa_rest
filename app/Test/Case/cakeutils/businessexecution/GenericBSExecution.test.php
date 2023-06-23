<?php
App::uses("ConnectionManager", "Model");
App::uses("Defaults", "Config/system");
App::uses("TestfkBS", "modules/cakeutils/business");

class GenericBSExecutionTest extends CakeTestCase {

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $maxid = "SELECT AUTO_INCREMENT FROM information_schema.TABLES";
        $maxid .= " WHERE TABLE_SCHEMA = '" . Defaults::get("db_name") . "' AND TABLE_NAME = 'testfks'";
        $data = $dbo->query($maxid);
        $result = $data[0]['TABLES'];
        $autoIncrement = $result['AUTO_INCREMENT'];

        $bs = new TestfkBS();
        $obj = $bs->instance();
        $obj['Testfk']['cod'] = "PROVA SALVATAGGIO";
        $id = $bs->save($obj);
        $this->assertEquals($id, $autoIncrement);

        // test inserted
        $search = "SELECT * FROM testfks WHERE cod='PROVA SALVATAGGIO'";
        $data = $dbo->query($search);
        $result = $data[0]['testfks'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'PROVA SALVATAGGIO');

        // reset
        $del = "DELETE FROM testfks WHERE cod='PROVA SALVATAGGIO'";
        $data = $dbo->query($del);
        $resetMaxid = "ALTER TABLE testfks AUTO_INCREMENT=" . ($autoIncrement - 1) . ";";
        $data = $dbo->query($resetMaxid);

        // verify reset
        $data = $dbo->query($maxid);
        $result = $data[0]['TABLES'];
        $this->assertEquals($result['AUTO_INCREMENT'], $autoIncrement);

        $sql = "SELECT COUNT(*) as num FROM testfks WHERE cod='PROVA SALVATAGGIO'";
        $data = $dbo->query($sql);
        $result = $data[0][0];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['num'] == 0, true);
    }

    public function testUpdateField() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        $bs = new TestfkBS();
        $obj = $bs->unique(1);
        $cod = $obj['Testfk']['cod'];

        $bs = new TestfkBS();
        $bs->updateField(1, "cod", "CODICE NUOVO");

        $bs = new TestfkBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Testfk']['id'], 1);
        $this->assertEquals($obj['Testfk']['cod'], "CODICE NUOVO");

        // reset
        $bs = new TestfkBS();
        $bs->updateField(1, "cod", $cod);

        // verify reset
        $bs = new TestfkBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Testfk']['id'], 1);
        $this->assertEquals($obj['Testfk']['cod'], "FK001");
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $maxid = "SELECT AUTO_INCREMENT FROM information_schema.TABLES";
        $maxid .= " WHERE TABLE_SCHEMA = '" . Defaults::get("db_name") . "' AND TABLE_NAME = 'testfks'";
        $data = $dbo->query($maxid);
        $result = $data[0]['TABLES'];
        $autoIncrement = $result['AUTO_INCREMENT'];

        $bs = new TestfkBS();
        $obj = $bs->instance();
        $obj['Testfk']['cod'] = "PROVA SALVATAGGIO";
        $id = $bs->save($obj);
        $this->assertEquals($id, $autoIncrement);

        // test inserted
        $search = "SELECT * FROM testfks WHERE cod='PROVA SALVATAGGIO'";
        $data = $dbo->query($search);
        $result = $data[0]['testfks'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'PROVA SALVATAGGIO');

        // delete
        $bs = new TestfkBS();
        $bs->delete($id);

        // reset
        $resetMaxid = "ALTER TABLE testfks AUTO_INCREMENT=" . ($autoIncrement - 1) . ";";
        $data = $dbo->query($resetMaxid);

        // verify reset
        $data = $dbo->query($maxid);
        $result = $data[0]['TABLES'];
        $this->assertEquals($result['AUTO_INCREMENT'], $autoIncrement);

        $sql = "SELECT COUNT(*) as num FROM testfks WHERE cod='PROVA SALVATAGGIO'";
        $data = $dbo->query($sql);
        $result = $data[0][0];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['num'] == 0, true);
    }
}