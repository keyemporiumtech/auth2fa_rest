<?php
App::uses("MailattachmentUI", "modules/communication/delegate");
App::uses("MailattachmentBS", "modules/communication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class MailattachmentUICoreTest extends CakeTestCase {

    public function addRecord() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mailattachments");
        $bs = new MailattachmentBS();
        $obj = $bs->instance();
        $obj['Mailattachment']['cod'] = "mioCodTest";
        $id = $bs->save($obj);
        return $autoIncrement;
    }

    public function removeRecord($autoIncrement) {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        MysqlUtilityTest::deleteLast($dbo, "mailattachments", "cod='mioCodTest'");
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mailattachments", $autoIncrement);
        MysqlUtilityTest::verifyDeleted($dbo, $this, "mailattachments", "cod='mioCodTest'");
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new MailattachmentUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Mailattachment']['id'], 1);
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
        $ui = new MailattachmentUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Mailattachment']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mailattachments");

        // obj
        $bs = new MailattachmentBS();
        $obj = $bs->instance();
        $obj['Mailattachment']['cod'] = "mioCodTest";

        // save
        $ui = new MailattachmentUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM mailattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['mailattachments'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "mailattachments", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mailattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "mailattachments", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new MailattachmentBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Mailattachment']['cod'] = "OthermioCodTest";

        // edit
        $ui = new MailattachmentUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new MailattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Mailattachment']['cod'], 'OthermioCodTest');

        // reset
        $ui = new MailattachmentUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new MailattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Mailattachment']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mailattachments");

        // insert
        $sql = "INSERT INTO mailattachments (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM mailattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['mailattachments'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new MailattachmentUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mailattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "mailattachments", "cod='mioCodTest'");
    }
}