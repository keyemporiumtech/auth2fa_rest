<?php
App::uses("ActivityaddressBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ActivityaddressBSCoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new ActivityaddressBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "activityaddresses");
            $bs = new ActivityaddressBS();
            $obj = $bs->instance();
            $obj['Activityaddress']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "activityaddresses", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityaddresses", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "activityaddresses", "cod='mioCodTest'");
        }
    }

    public function testUnique() {
        $autoIncrement = $this->addRecord();
        $bs = new ActivityaddressBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Activityaddress']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testAll() {
        $autoIncrement = $this->addRecord();
        $bs = new ActivityaddressBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Activityaddress']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "activityaddresses");

        // obj
        $bs = new ActivityaddressBS();
        $obj = $bs->instance();
        $obj['Activityaddress']['cod'] = "mioCodTest";

        // save
        $bs = new ActivityaddressBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM activityaddresses WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['activityaddresses'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "activityaddresses", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityaddresses", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "activityaddresses", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new ActivityaddressBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Activityaddress']['cod'] = "OthermioCodTest";

        // edit
        $bs = new ActivityaddressBS();
        $id = $bs->save($objNew);

        // test
        $bs = new ActivityaddressBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Activityaddress']['cod'], 'OthermioCodTest');

        // reset
        $bs = new ActivityaddressBS();
        $id = $bs->save($obj);

        // test
        $bs = new ActivityaddressBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Activityaddress']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "activityaddresses");

        // insert
        $sql = "INSERT INTO activityaddresses (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM activityaddresses WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['activityaddresses'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new ActivityaddressBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityaddresses", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "activityaddresses", "cod='mioCodTest'");
    }
}