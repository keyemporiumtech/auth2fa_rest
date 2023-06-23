<?php
App::uses("ProfessionBS", "modules/work_cv/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ProfessionBSCoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new ProfessionBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "professions");
            $bs = new ProfessionBS();
            $obj = $bs->instance();
            $obj['Profession']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "professions", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "professions", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "professions", "cod='mioCodTest'");
        }
    }

    public function testUnique() {
        $autoIncrement = $this->addRecord();
        $bs = new ProfessionBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Profession']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testAll() {
        $autoIncrement = $this->addRecord();
        $bs = new ProfessionBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Profession']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "professions");

        // obj
        $bs = new ProfessionBS();
        $obj = $bs->instance();
        $obj['Profession']['cod'] = "mioCodTest";

        // save
        $bs = new ProfessionBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM professions WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['professions'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "professions", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "professions", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "professions", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new ProfessionBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Profession']['cod'] = "OthermioCodTest";

        // edit
        $bs = new ProfessionBS();
        $id = $bs->save($objNew);

        // test
        $bs = new ProfessionBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Profession']['cod'], 'OthermioCodTest');

        // reset
        $bs = new ProfessionBS();
        $id = $bs->save($obj);

        // test
        $bs = new ProfessionBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Profession']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "professions");

        // insert
        $sql = "INSERT INTO professions (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM professions WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['professions'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new ProfessionBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "professions", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "professions", "cod='mioCodTest'");
    }
}