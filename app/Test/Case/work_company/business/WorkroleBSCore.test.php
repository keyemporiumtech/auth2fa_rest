<?php
App::uses("WorkroleBS", "modules/work_company/business");
App::uses("MysqlUtilityTest", "Test/utility");

class WorkroleBSCoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new WorkroleBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workroles");
            $bs = new WorkroleBS();
            $obj = $bs->instance();
            $obj['Workrole']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "workroles", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workroles", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "workroles", "cod='mioCodTest'");
        }
    }

    public function testUnique() {
        $autoIncrement = $this->addRecord();
        $bs = new WorkroleBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Workrole']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testAll() {
        $autoIncrement = $this->addRecord();
        $bs = new WorkroleBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Workrole']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workroles");

        // obj
        $bs = new WorkroleBS();
        $obj = $bs->instance();
        $obj['Workrole']['cod'] = "mioCodTest";

        // save
        $bs = new WorkroleBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM workroles WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['workroles'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "workroles", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workroles", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "workroles", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new WorkroleBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Workrole']['cod'] = "OthermioCodTest";

        // edit
        $bs = new WorkroleBS();
        $id = $bs->save($objNew);

        // test
        $bs = new WorkroleBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Workrole']['cod'], 'OthermioCodTest');

        // reset
        $bs = new WorkroleBS();
        $id = $bs->save($obj);

        // test
        $bs = new WorkroleBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Workrole']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workroles");

        // insert
        $sql = "INSERT INTO workroles (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM workroles WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['workroles'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new WorkroleBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workroles", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "workroles", "cod='mioCodTest'");
    }
}