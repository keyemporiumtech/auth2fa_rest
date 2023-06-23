<?php
App::uses("LanguageUI", "modules/localesystem/delegate");
App::uses("LanguageBS", "modules/localesystem/business");
App::uses("MysqlUtilityTest", "Test/utility");

class LanguageUICoreTest extends CakeTestCase {

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

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new LanguageUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Language']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testTable() {
        $autoIncrement = $this->addRecord();
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 1;
        $conditions = array(
            $condition,
        );
        $ui = new LanguageUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Language']['id'], 1);
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
        $ui = new LanguageUI();
        $id = $ui->save($obj);

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
        $ui = new LanguageUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new LanguageBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Language']['cod'], 'OthermioCodTest');

        // reset
        $ui = new LanguageUI();
        $id = $ui->edit($id, $obj);

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
        $ui = new LanguageUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "languages", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "languages", "cod='mioCodTest'");
    }
}