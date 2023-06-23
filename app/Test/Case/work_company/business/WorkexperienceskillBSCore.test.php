<?php
App::uses("WorkexperienceskillBS", "modules/work_company/business");
App::uses("MysqlUtilityTest", "Test/utility");

class WorkexperienceskillBSCoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new WorkexperienceskillBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workexperienceskills");
            $bs = new WorkexperienceskillBS();
            $obj = $bs->instance();
            $obj['Workexperienceskill']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "workexperienceskills", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workexperienceskills", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "workexperienceskills", "cod='mioCodTest'");
        }
    }

    public function testUnique() {
        $autoIncrement = $this->addRecord();
        $bs = new WorkexperienceskillBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Workexperienceskill']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testAll() {
        $autoIncrement = $this->addRecord();
        $bs = new WorkexperienceskillBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Workexperienceskill']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workexperienceskills");

        // obj
        $bs = new WorkexperienceskillBS();
        $obj = $bs->instance();
        $obj['Workexperienceskill']['cod'] = "mioCodTest";

        // save
        $bs = new WorkexperienceskillBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM workexperienceskills WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['workexperienceskills'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "workexperienceskills", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workexperienceskills", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "workexperienceskills", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new WorkexperienceskillBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Workexperienceskill']['cod'] = "OthermioCodTest";

        // edit
        $bs = new WorkexperienceskillBS();
        $id = $bs->save($objNew);

        // test
        $bs = new WorkexperienceskillBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Workexperienceskill']['cod'], 'OthermioCodTest');

        // reset
        $bs = new WorkexperienceskillBS();
        $id = $bs->save($obj);

        // test
        $bs = new WorkexperienceskillBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Workexperienceskill']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workexperienceskills");

        // insert
        $sql = "INSERT INTO workexperienceskills (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM workexperienceskills WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['workexperienceskills'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new WorkexperienceskillBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workexperienceskills", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "workexperienceskills", "cod='mioCodTest'");
    }
}