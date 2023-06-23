<?php
App::uses("CityUI", "modules/localesystem/delegate");
App::uses("CityBS", "modules/localesystem/business");
App::uses("MysqlUtilityTest", "Test/utility");

class CityUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new CityBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "cities");
            $bs = new CityBS();
            $obj = $bs->instance();
            $obj['City']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "cities", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "cities", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "cities", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new CityUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['City']['id'], 1);
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
        $ui = new CityUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['City']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "cities");

        // obj
        $bs = new CityBS();
        $obj = $bs->instance();
        $obj['City']['cod'] = "mioCodTest";

        // save
        $ui = new CityUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM cities WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['cities'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "cities", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "cities", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "cities", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new CityBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['City']['cod'] = "OthermioCodTest";

        // edit
        $ui = new CityUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new CityBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['City']['cod'], 'OthermioCodTest');

        // reset
        $ui = new CityUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new CityBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['City']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "cities");

        // insert
        $sql = "INSERT INTO cities (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM cities WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['cities'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new CityUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "cities", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "cities", "cod='mioCodTest'");
    }
}