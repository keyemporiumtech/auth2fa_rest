<?php
App::uses("WorkuserBS", "modules/work_profile/business");
App::uses("MysqlUtilityTest", "Test/utility");

class WorkuserBSCoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new WorkuserBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workusers");
            $bs = new WorkuserBS();
            $obj = $bs->instance();
            $obj['Workuser']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "workusers", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workusers", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "workusers", "cod='mioCodTest'");
        }
    }

    public function testUnique() {
        $autoIncrement = $this->addRecord();
        $bs = new WorkuserBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Workuser']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testAll() {
        $autoIncrement = $this->addRecord();
        $bs = new WorkuserBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Workuser']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workusers");

        // obj
        $bs = new WorkuserBS();
        $obj = $bs->instance();
        $obj['Workuser']['cod'] = "mioCodTest";

        // save
        $bs = new WorkuserBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM workusers WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['workusers'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "workusers", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workusers", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "workusers", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new WorkuserBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Workuser']['cod'] = "OthermioCodTest";

        // edit
        $bs = new WorkuserBS();
        $id = $bs->save($objNew);

        // test
        $bs = new WorkuserBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Workuser']['cod'], 'OthermioCodTest');

        // reset
        $bs = new WorkuserBS();
        $id = $bs->save($obj);

        // test
        $bs = new WorkuserBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Workuser']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workusers");

        // insert
        $sql = "INSERT INTO workusers (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM workusers WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['workusers'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new WorkuserBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workusers", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "workusers", "cod='mioCodTest'");
    }
}