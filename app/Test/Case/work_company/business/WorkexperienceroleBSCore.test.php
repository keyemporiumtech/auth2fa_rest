<?php
App::uses("WorkexperienceroleBS", "modules/work_company/business");
App::uses("MysqlUtilityTest", "Test/utility");

class WorkexperienceroleBSCoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new WorkexperienceroleBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workexperienceroles");
            $bs = new WorkexperienceroleBS();
            $obj = $bs->instance();
            $obj['Workexperiencerole']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "workexperienceroles", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workexperienceroles", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "workexperienceroles", "cod='mioCodTest'");
        }
    }

    public function testUnique() {
        $autoIncrement = $this->addRecord();
        $bs = new WorkexperienceroleBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Workexperiencerole']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testAll() {
        $autoIncrement = $this->addRecord();
        $bs = new WorkexperienceroleBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Workexperiencerole']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workexperienceroles");

        // obj
        $bs = new WorkexperienceroleBS();
        $obj = $bs->instance();
        $obj['Workexperiencerole']['cod'] = "mioCodTest";

        // save
        $bs = new WorkexperienceroleBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM workexperienceroles WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['workexperienceroles'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "workexperienceroles", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workexperienceroles", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "workexperienceroles", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new WorkexperienceroleBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Workexperiencerole']['cod'] = "OthermioCodTest";

        // edit
        $bs = new WorkexperienceroleBS();
        $id = $bs->save($objNew);

        // test
        $bs = new WorkexperienceroleBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Workexperiencerole']['cod'], 'OthermioCodTest');

        // reset
        $bs = new WorkexperienceroleBS();
        $id = $bs->save($obj);

        // test
        $bs = new WorkexperienceroleBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Workexperiencerole']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workexperienceroles");

        // insert
        $sql = "INSERT INTO workexperienceroles (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM workexperienceroles WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['workexperienceroles'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new WorkexperienceroleBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workexperienceroles", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "workexperienceroles", "cod='mioCodTest'");
    }
}