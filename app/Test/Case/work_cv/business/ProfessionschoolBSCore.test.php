<?php
App::uses("ProfessionschoolBS", "modules/work_cv/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ProfessionschoolBSCoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new ProfessionschoolBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "professionschools");
            $bs = new ProfessionschoolBS();
            $obj = $bs->instance();
            $obj['Professionschool']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "professionschools", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "professionschools", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "professionschools", "cod='mioCodTest'");
        }
    }

    public function testUnique() {
        $autoIncrement = $this->addRecord();
        $bs = new ProfessionschoolBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Professionschool']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testAll() {
        $autoIncrement = $this->addRecord();
        $bs = new ProfessionschoolBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Professionschool']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "professionschools");

        // obj
        $bs = new ProfessionschoolBS();
        $obj = $bs->instance();
        $obj['Professionschool']['cod'] = "mioCodTest";

        // save
        $bs = new ProfessionschoolBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM professionschools WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['professionschools'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "professionschools", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "professionschools", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "professionschools", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new ProfessionschoolBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Professionschool']['cod'] = "OthermioCodTest";

        // edit
        $bs = new ProfessionschoolBS();
        $id = $bs->save($objNew);

        // test
        $bs = new ProfessionschoolBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Professionschool']['cod'], 'OthermioCodTest');

        // reset
        $bs = new ProfessionschoolBS();
        $id = $bs->save($obj);

        // test
        $bs = new ProfessionschoolBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Professionschool']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "professionschools");

        // insert
        $sql = "INSERT INTO professionschools (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM professionschools WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['professionschools'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new ProfessionschoolBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "professionschools", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "professionschools", "cod='mioCodTest'");
    }
}