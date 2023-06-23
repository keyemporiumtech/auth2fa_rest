<?php
App::uses("MailcidUI", "modules/communication/delegate");
App::uses("MailcidBS", "modules/communication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class MailcidUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new MailcidBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mailcids");
            $bs = new MailcidBS();
            $obj = $bs->instance();
            $obj['Mailcid']['cid'] = "mioCidTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "mailcids", "cid='mioCidTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mailcids", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "mailcids", "cid='mioCidTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new MailcidUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Mailcid']['id'], 1);
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
        $ui = new MailcidUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Mailcid']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mailcids");

        // obj
        $bs = new MailcidBS();
        $obj = $bs->instance();
        $obj['Mailcid']['cid'] = "mioCidTest";

        // save
        $ui = new MailcidUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM mailcids WHERE cid='mioCidTest'";
        $data = $dbo->query($search);
        $result = $data[0]['mailcids'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cid'], 'mioCidTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "mailcids", "cid='mioCidTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mailcids", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "mailcids", "cid='mioCidTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new MailcidBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Mailcid']['cid'] = "OthermioCidTest";

        // edit
        $ui = new MailcidUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new MailcidBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Mailcid']['cid'], 'OthermioCidTest');

        // reset
        $ui = new MailcidUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new MailcidBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Mailcid']['cid'] == 'OthermioCidTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mailcids");

        // insert
        $sql = "INSERT INTO mailcids (id,cid,created) VALUES";
        $sql .= " (NULL, 'mioCidTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM mailcids WHERE cid='mioCidTest'";
        $data = $dbo->query($search);
        $result = $data[0]['mailcids'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cid'], 'mioCidTest');

        // delete
        $ui = new MailcidUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mailcids", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "mailcids", "cid='mioCidTest'");
    }
}