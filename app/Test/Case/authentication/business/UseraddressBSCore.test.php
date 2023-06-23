<?php
App::uses("UseraddressBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class UseraddressBSCoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new UseraddressBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "useraddresses");
            $bs = new UseraddressBS();
            $obj = $bs->instance();
            $obj['Useraddress']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "useraddresses", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "useraddresses", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "useraddresses", "cod='mioCodTest'");
        }
    }

    public function testUnique() {
        $autoIncrement = $this->addRecord();
        $bs = new UseraddressBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Useraddress']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testAll() {
        $autoIncrement = $this->addRecord();
        $bs = new UseraddressBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Useraddress']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "useraddresses");

        // obj
        $bs = new UseraddressBS();
        $obj = $bs->instance();
        $obj['Useraddress']['cod'] = "mioCodTest";

        // save
        $bs = new UseraddressBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM useraddresses WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['useraddresses'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "useraddresses", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "useraddresses", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "useraddresses", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new UseraddressBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Useraddress']['cod'] = "OthermioCodTest";

        // edit
        $bs = new UseraddressBS();
        $id = $bs->save($objNew);

        // test
        $bs = new UseraddressBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Useraddress']['cod'], 'OthermioCodTest');

        // reset
        $bs = new UseraddressBS();
        $id = $bs->save($obj);

        // test
        $bs = new UseraddressBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Useraddress']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "useraddresses");

        // insert
        $sql = "INSERT INTO useraddresses (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM useraddresses WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['useraddresses'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new UseraddressBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "useraddresses", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "useraddresses", "cod='mioCodTest'");
    }
}