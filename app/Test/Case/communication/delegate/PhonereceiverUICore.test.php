<?php
App::uses("PhonereceiverUI", "modules/communication/delegate");
App::uses("PhonereceiverBS", "modules/communication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class PhonereceiverUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new PhonereceiverBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "phonereceivers");
            $bs = new PhonereceiverBS();
            $obj = $bs->instance();
            $obj['Phonereceiver']['receivername'] = "mioReceivernameTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "phonereceivers", "receivername='mioReceivernameTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "phonereceivers", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "phonereceivers", "receivername='mioReceivernameTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new PhonereceiverUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Phonereceiver']['id'], 1);
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
        $ui = new PhonereceiverUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Phonereceiver']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "phonereceivers");

        // obj
        $bs = new PhonereceiverBS();
        $obj = $bs->instance();
        $obj['Phonereceiver']['receivername'] = "mioReceivernameTest";

        // save
        $ui = new PhonereceiverUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM phonereceivers WHERE receivername='mioReceivernameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['phonereceivers'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['receivername'], 'mioReceivernameTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "phonereceivers", "receivername='mioReceivernameTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "phonereceivers", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "phonereceivers", "receivername='mioReceivernameTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new PhonereceiverBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Phonereceiver']['receivername'] = "OthermioReceivernameTest";

        // edit
        $ui = new PhonereceiverUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new PhonereceiverBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Phonereceiver']['receivername'], 'OthermioReceivernameTest');

        // reset
        $ui = new PhonereceiverUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new PhonereceiverBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Phonereceiver']['receivername'] == 'OthermioReceivernameTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "phonereceivers");

        // insert
        $sql = "INSERT INTO phonereceivers (id,receivername,created) VALUES";
        $sql .= " (NULL, 'mioReceivernameTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM phonereceivers WHERE receivername='mioReceivernameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['phonereceivers'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['receivername'], 'mioReceivernameTest');

        // delete
        $ui = new PhonereceiverUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "phonereceivers", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "phonereceivers", "receivername='mioReceivernameTest'");
    }
}