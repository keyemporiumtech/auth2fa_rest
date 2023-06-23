<?php
App::uses("MailreceiverUI", "modules/communication/delegate");
App::uses("MailreceiverBS", "modules/communication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class MailreceiverUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new MailreceiverBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mailreceivers");
            $bs = new MailreceiverBS();
            $obj = $bs->instance();
            $obj['Mailreceiver']['receivername'] = "mioReceivernameTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "mailreceivers", "receivername='mioReceivernameTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mailreceivers", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "mailreceivers", "receivername='mioReceivernameTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new MailreceiverUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Mailreceiver']['id'], 1);
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
        $ui = new MailreceiverUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Mailreceiver']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mailreceivers");

        // obj
        $bs = new MailreceiverBS();
        $obj = $bs->instance();
        $obj['Mailreceiver']['receivername'] = "mioReceivernameTest";

        // save
        $ui = new MailreceiverUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM mailreceivers WHERE receivername='mioReceivernameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['mailreceivers'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['receivername'], 'mioReceivernameTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "mailreceivers", "receivername='mioReceivernameTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mailreceivers", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "mailreceivers", "receivername='mioReceivernameTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new MailreceiverBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Mailreceiver']['receivername'] = "OthermioReceivernameTest";

        // edit
        $ui = new MailreceiverUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new MailreceiverBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Mailreceiver']['receivername'], 'OthermioReceivernameTest');

        // reset
        $ui = new MailreceiverUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new MailreceiverBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Mailreceiver']['receivername'] == 'OthermioReceivernameTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mailreceivers");

        // insert
        $sql = "INSERT INTO mailreceivers (id,receivername,created) VALUES";
        $sql .= " (NULL, 'mioReceivernameTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM mailreceivers WHERE receivername='mioReceivernameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['mailreceivers'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['receivername'], 'mioReceivernameTest');

        // delete
        $ui = new MailreceiverUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mailreceivers", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "mailreceivers", "receivername='mioReceivernameTest'");
    }
}