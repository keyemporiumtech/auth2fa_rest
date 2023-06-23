<?php
App::uses("PhoneUI", "modules/communication/delegate");
App::uses("PhoneBS", "modules/communication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class PhoneUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new PhoneBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "phones");
            $bs = new PhoneBS();
            $obj = $bs->instance();
            $obj['Phone']['sendername'] = "mioSendernameTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "phones", "sendername='mioSendernameTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "phones", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "phones", "sendername='mioSendernameTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new PhoneUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Phone']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testTable() {
        $autoIncrement = $this->addRecord();
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 1;
        $conditions = array(
            $condition,
        );
        $ui = new PhoneUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Phone']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "phones");

        // obj
        $bs = new PhoneBS();
        $obj = $bs->instance();
        $obj['Phone']['sendername'] = "mioSendernameTest";

        // save
        $ui = new PhoneUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM phones WHERE sendername='mioSendernameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['phones'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['sendername'], 'mioSendernameTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "phones", "sendername='mioSendernameTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "phones", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "phones", "sendername='mioSendernameTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new PhoneBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Phone']['sendername'] = "OthermioSendernameTest";

        // edit
        $ui = new PhoneUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new PhoneBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Phone']['sendername'], 'OthermioSendernameTest');

        // reset
        $ui = new PhoneUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new PhoneBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Phone']['sendername'] == 'OthermioSendernameTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "phones");

        // insert
        $sql = "INSERT INTO phones (id,sendername,created) VALUES";
        $sql .= " (NULL, 'mioSendernameTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM phones WHERE sendername='mioSendernameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['phones'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['sendername'], 'mioSendernameTest');

        // delete
        $ui = new PhoneUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "phones", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "phones", "sendername='mioSendernameTest'");
    }
}