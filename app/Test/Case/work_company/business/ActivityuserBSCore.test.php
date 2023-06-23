<?php
App::uses("ActivityuserBS", "modules/work_company/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ActivityuserBSCoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new ActivityuserBS();
        $num = $bs->count();
        if ($num == 0) {
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "activityusers");
            $bs = new ActivityuserBS();
            $obj = $bs->instance();
            $obj['Activityuser']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "activityusers", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityusers", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "activityusers", "cod='mioCodTest'");
        }
    }

    public function testUnique() {
        $autoIncrement = $this->addRecord();
        $bs = new ActivityuserBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Activityuser']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testAll() {
        $autoIncrement = $this->addRecord();
        $bs = new ActivityuserBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Activityuser']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "activityusers");

        // obj
        $bs = new ActivityuserBS();
        $obj = $bs->instance();
        $obj['Activityuser']['cod'] = "mioCodTest";

        // save
        $bs = new ActivityuserBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM activityusers WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['activityusers'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "activityusers", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityusers", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "activityusers", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new ActivityuserBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Activityuser']['cod'] = "OthermioCodTest";

        // edit
        $bs = new ActivityuserBS();
        $id = $bs->save($objNew);

        // test
        $bs = new ActivityuserBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Activityuser']['cod'], 'OthermioCodTest');

        // reset
        $bs = new ActivityuserBS();
        $id = $bs->save($obj);

        // test
        $bs = new ActivityuserBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Activityuser']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "activityusers");

        // insert
        $sql = "INSERT INTO activityusers (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM activityusers WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['activityusers'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new ActivityuserBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityusers", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "activityusers", "cod='mioCodTest'");
    }
}