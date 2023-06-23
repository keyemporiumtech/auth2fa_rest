<?php
App::uses("MailUI", "modules/communication/delegate");
App::uses("MailBS", "modules/communication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class MailUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new MailBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mails");
            $bs = new MailBS();
            $obj = $bs->instance();
            $obj['Mail']['ipname'] = "mioIpnameTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "mails", "ipname='mioIpnameTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mails", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "mails", "ipname='mioIpnameTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new MailUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Mail']['id'], 1);
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
        $ui = new MailUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Mail']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mails");

        // obj
        $bs = new MailBS();
        $obj = $bs->instance();
        $obj['Mail']['ipname'] = "mioIpnameTest";

        // save
        $ui = new MailUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM mails WHERE ipname='mioIpnameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['mails'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['ipname'], 'mioIpnameTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "mails", "ipname='mioIpnameTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mails", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "mails", "ipname='mioIpnameTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new MailBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Mail']['ipname'] = "OthermioIpnameTest";

        // edit
        $ui = new MailUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new MailBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Mail']['ipname'], 'OthermioIpnameTest');

        // reset
        $ui = new MailUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new MailBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Mail']['ipname'] == 'OthermioIpnameTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mails");

        // insert
        $sql = "INSERT INTO mails (id,ipname,created) VALUES";
        $sql .= " (NULL, 'mioIpnameTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM mails WHERE ipname='mioIpnameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['mails'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['ipname'], 'mioIpnameTest');

        // delete
        $ui = new MailUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mails", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "mails", "ipname='mioIpnameTest'");
    }
}