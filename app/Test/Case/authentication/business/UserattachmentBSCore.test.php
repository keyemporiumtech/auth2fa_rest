<?php
App::uses("UserattachmentBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class UserattachmentBSCoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new UserattachmentBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "userattachments");
            $bs = new UserattachmentBS();
            $obj = $bs->instance();
            $obj['Userattachment']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "userattachments", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userattachments", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "userattachments", "cod='mioCodTest'");
        }
    }

    public function testUnique() {
        $autoIncrement = $this->addRecord();
        $bs = new UserattachmentBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Userattachment']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testAll() {
        $autoIncrement = $this->addRecord();
        $bs = new UserattachmentBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Userattachment']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "userattachments");

        // obj
        $bs = new UserattachmentBS();
        $obj = $bs->instance();
        $obj['Userattachment']['cod'] = "mioCodTest";

        // save
        $bs = new UserattachmentBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM userattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['userattachments'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "userattachments", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "userattachments", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new UserattachmentBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Userattachment']['cod'] = "OthermioCodTest";

        // edit
        $bs = new UserattachmentBS();
        $id = $bs->save($objNew);

        // test
        $bs = new UserattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Userattachment']['cod'], 'OthermioCodTest');

        // reset
        $bs = new UserattachmentBS();
        $id = $bs->save($obj);

        // test
        $bs = new UserattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Userattachment']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "userattachments");

        // insert
        $sql = "INSERT INTO userattachments (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM userattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['userattachments'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new UserattachmentBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "userattachments", "cod='mioCodTest'");
    }
}