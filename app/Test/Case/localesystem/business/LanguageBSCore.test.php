<?php
App::uses("LanguageBS", "modules/localesystem/business");
App::uses("MysqlUtilityTest", "Test/utility");

class LanguageBSCoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new LanguageBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "languages");
            $bs = new LanguageBS();
            $obj = $bs->instance();
            $obj['Language']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "languages", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "languages", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "languages", "cod='mioCodTest'");
        }
    }

    public function testUnique() {
        $autoIncrement = $this->addRecord();
        $bs = new LanguageBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Language']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testAll() {
        $autoIncrement = $this->addRecord();
        $bs = new LanguageBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Language']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "languages");

        // obj
        $bs = new LanguageBS();
        $obj = $bs->instance();
        $obj['Language']['cod'] = "mioCodTest";

        // save
        $bs = new LanguageBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM languages WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['languages'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "languages", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "languages", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "languages", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new LanguageBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Language']['cod'] = "OthermioCodTest";

        // edit
        $bs = new LanguageBS();
        $id = $bs->save($objNew);

        // test
        $bs = new LanguageBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Language']['cod'], 'OthermioCodTest');

        // reset
        $bs = new LanguageBS();
        $id = $bs->save($obj);

        // test
        $bs = new LanguageBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Language']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "languages");

        // insert
        $sql = "INSERT INTO languages (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM languages WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['languages'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new LanguageBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "languages", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "languages", "cod='mioCodTest'");
    }
}