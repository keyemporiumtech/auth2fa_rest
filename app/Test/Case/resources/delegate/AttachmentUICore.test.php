<?php
App::uses("AttachmentUI", "modules/resources/delegate");
App::uses("AttachmentBS", "modules/resources/business");
App::uses("MysqlUtilityTest", "Test/utility");

class AttachmentUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new AttachmentBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "attachments");
            $bs = new AttachmentBS();
            $obj = $bs->instance();
            $obj['Attachment']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "attachments", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "attachments", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "attachments", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new AttachmentUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Attachment']['id'], 1);
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
        $ui = new AttachmentUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Attachment']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "attachments");

        // obj
        $bs = new AttachmentBS();
        $obj = $bs->instance();
        $obj['Attachment']['cod'] = "mioCodTest";

        // save
        $ui = new AttachmentUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM attachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['attachments'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "attachments", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "attachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "attachments", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new AttachmentBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Attachment']['cod'] = "OthermioCodTest";

        // edit
        $ui = new AttachmentUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new AttachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Attachment']['cod'], 'OthermioCodTest');

        // reset
        $ui = new AttachmentUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new AttachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Attachment']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "attachments");

        // insert
        $sql = "INSERT INTO attachments (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM attachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['attachments'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new AttachmentUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "attachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "attachments", "cod='mioCodTest'");
    }
}