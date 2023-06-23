<?php
App::uses("PocketreservesettingUI", "modules/shop_warehouse/delegate");
App::uses("PocketreservesettingBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class PocketreservesettingUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new PocketreservesettingBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pocketreservesettings");
            $bs = new PocketreservesettingBS();
            $obj = $bs->instance();
            $obj['Pocketreservesetting']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "pocketreservesettings", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pocketreservesettings", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "pocketreservesettings", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new PocketreservesettingUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Pocketreservesetting']['id'], 1);
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
        $ui = new PocketreservesettingUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Pocketreservesetting']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pocketreservesettings");

        // obj
        $bs = new PocketreservesettingBS();
        $obj = $bs->instance();
        $obj['Pocketreservesetting']['cod'] = "mioCodTest";

        // save
        $ui = new PocketreservesettingUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM pocketreservesettings WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['pocketreservesettings'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "pocketreservesettings", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pocketreservesettings", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "pocketreservesettings", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new PocketreservesettingBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Pocketreservesetting']['cod'] = "OthermioCodTest";

        // edit
        $ui = new PocketreservesettingUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new PocketreservesettingBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Pocketreservesetting']['cod'], 'OthermioCodTest');

        // reset
        $ui = new PocketreservesettingUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new PocketreservesettingBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Pocketreservesetting']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pocketreservesettings");

        // insert
        $sql = "INSERT INTO pocketreservesettings (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM pocketreservesettings WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['pocketreservesettings'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new PocketreservesettingUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pocketreservesettings", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "pocketreservesettings", "cod='mioCodTest'");
    }
}