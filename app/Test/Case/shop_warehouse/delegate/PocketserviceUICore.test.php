<?php
App::uses("PocketserviceUI", "modules/shop_warehouse/delegate");
App::uses("PocketserviceBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class PocketserviceUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new PocketserviceBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pocketservices");
            $bs = new PocketserviceBS();
            $obj = $bs->instance();
            $obj['Pocketservice']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "pocketservices", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pocketservices", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "pocketservices", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new PocketserviceUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Pocketservice']['id'], 1);
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
        $ui = new PocketserviceUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Pocketservice']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pocketservices");

        // obj
        $bs = new PocketserviceBS();
        $obj = $bs->instance();
        $obj['Pocketservice']['cod'] = "mioCodTest";

        // save
        $ui = new PocketserviceUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM pocketservices WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['pocketservices'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "pocketservices", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pocketservices", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "pocketservices", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new PocketserviceBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Pocketservice']['cod'] = "OthermioCodTest";

        // edit
        $ui = new PocketserviceUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new PocketserviceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Pocketservice']['cod'], 'OthermioCodTest');

        // reset
        $ui = new PocketserviceUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new PocketserviceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Pocketservice']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pocketservices");

        // insert
        $sql = "INSERT INTO pocketservices (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM pocketservices WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['pocketservices'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new PocketserviceUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pocketservices", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "pocketservices", "cod='mioCodTest'");
    }
}