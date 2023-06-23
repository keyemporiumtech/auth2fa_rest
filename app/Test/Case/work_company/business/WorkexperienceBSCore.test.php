<?php
App::uses("WorkexperienceBS", "modules/work_company/business");
App::uses("MysqlUtilityTest", "Test/utility");

class WorkexperienceBSCoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new WorkexperienceBS();
        $num = $bs->count();
        if ($num == 0) {
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workexperiences");
            $bs = new WorkexperienceBS();
            $obj = $bs->instance();
            $obj['Workexperience']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "workexperiences", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workexperiences", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "workexperiences", "cod='mioCodTest'");
        }
    }

    public function testUnique() {
        $autoIncrement = $this->addRecord();
        $bs = new WorkexperienceBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Workexperience']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testAll() {
        $autoIncrement = $this->addRecord();
        $bs = new WorkexperienceBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Workexperience']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workexperiences");

        // obj
        $bs = new WorkexperienceBS();
        $obj = $bs->instance();
        $obj['Workexperience']['cod'] = "mioCodTest";

        // save
        $bs = new WorkexperienceBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM workexperiences WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['workexperiences'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "workexperiences", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workexperiences", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "workexperiences", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new WorkexperienceBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Workexperience']['cod'] = "OthermioCodTest";

        // edit
        $bs = new WorkexperienceBS();
        $id = $bs->save($objNew);

        // test
        $bs = new WorkexperienceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Workexperience']['cod'], 'OthermioCodTest');

        // reset
        $bs = new WorkexperienceBS();
        $id = $bs->save($obj);

        // test
        $bs = new WorkexperienceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Workexperience']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workexperiences");

        // insert
        $sql = "INSERT INTO workexperiences (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM workexperiences WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['workexperiences'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new WorkexperienceBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workexperiences", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "workexperiences", "cod='mioCodTest'");
    }
}