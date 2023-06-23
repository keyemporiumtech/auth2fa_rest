<?php
App::uses("MultilanguageUI", "modules/localesystem/delegate");
App::uses("MultilanguageBS", "modules/localesystem/business");
App::uses("MysqlUtilityTest", "Test/utility");

class MultilanguageUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new MultilanguageBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "multilanguages");
            $bs = new MultilanguageBS();
            $obj = $bs->instance();
            $obj['Multilanguage']['tablename'] = "miaTablenameTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "multilanguages", "tablename='miaTablenameTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "multilanguages", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "multilanguages", "tablename='miaTablenameTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new MultilanguageUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Multilanguage']['id'], 1);
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
        $ui = new MultilanguageUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Multilanguage']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "multilanguages");

        // obj
        $bs = new MultilanguageBS();
        $obj = $bs->instance();
        $obj['Multilanguage']['tablename'] = "miaTablenameTest";

        // save
        $ui = new MultilanguageUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM multilanguages WHERE tablename='miaTablenameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['multilanguages'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['tablename'], 'miaTablenameTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "multilanguages", "tablename='miaTablenameTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "multilanguages", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "multilanguages", "tablename='miaTablenameTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new MultilanguageBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Multilanguage']['tablename'] = "OthermiaTablenameTest";

        // edit
        $ui = new MultilanguageUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new MultilanguageBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Multilanguage']['tablename'], 'OthermiaTablenameTest');

        // reset
        $ui = new MultilanguageUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new MultilanguageBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Multilanguage']['tablename'] == 'OthermiaTablenameTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "multilanguages");

        // insert
        $sql = "INSERT INTO multilanguages (id,tablename,created) VALUES";
        $sql .= " (NULL, 'miaTablenameTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM multilanguages WHERE tablename='miaTablenameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['multilanguages'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['tablename'], 'miaTablenameTest');

        // delete
        $ui = new MultilanguageUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "multilanguages", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "multilanguages", "tablename='miaTablenameTest'");
    }
}