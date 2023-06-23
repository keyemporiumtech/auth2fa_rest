<?php
App::uses("MailattachmentBS", "modules/resources/business");
App::uses("MysqlUtilityTest", "Test/utility");

class MailattachmentBSCoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new MailattachmentBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mailattachments");
            $bs = new MailattachmentBS();
            $obj = $bs->instance();
            $obj['Mailattachment']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "mailattachments", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mailattachments", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "mailattachments", "cod='mioCodTest'");
        }
    }

    public function testUnique() {
        $autoIncrement = $this->addRecord();
        $bs = new MailattachmentBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Mailattachment']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testAll() {
        $autoIncrement = $this->addRecord();
        $bs = new MailattachmentBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Mailattachment']['id'], 1);
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
        $bs = new MailattachmentBS();
        $id = $bs->save($obj);

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
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new MailattachmentBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Mailattachment']['cod'] = "OthermioCodTest";

        // edit
        $bs = new MailattachmentBS();
        $id = $bs->save($objNew);

        // test
        $bs = new MailattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Mailattachment']['cod'], 'OthermioCodTest');

        // reset
        $bs = new MailattachmentBS();
        $id = $bs->save($obj);

        // test
        $bs = new MailattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Mailattachment']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
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
        $bs = new MailattachmentBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mailattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "mailattachments", "cod='mioCodTest'");
    }
}