<?php
App::uses("PocketproductUI", "modules/shop_warehouse/delegate");
App::uses("PocketproductBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class PocketproductUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new PocketproductBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pocketproducts");
            $bs = new PocketproductBS();
            $obj = $bs->instance();
            $obj['Pocketproduct']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "pocketproducts", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pocketproducts", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "pocketproducts", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new PocketproductUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Pocketproduct']['id'], 1);
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
        $ui = new PocketproductUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Pocketproduct']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pocketproducts");

        // obj
        $bs = new PocketproductBS();
        $obj = $bs->instance();
        $obj['Pocketproduct']['cod'] = "mioCodTest";

        // save
        $ui = new PocketproductUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM pocketproducts WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['pocketproducts'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "pocketproducts", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pocketproducts", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "pocketproducts", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new PocketproductBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Pocketproduct']['cod'] = "OthermioCodTest";

        // edit
        $ui = new PocketproductUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new PocketproductBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Pocketproduct']['cod'], 'OthermioCodTest');

        // reset
        $ui = new PocketproductUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new PocketproductBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Pocketproduct']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pocketproducts");

        // insert
        $sql = "INSERT INTO pocketproducts (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM pocketproducts WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['pocketproducts'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new PocketproductUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pocketproducts", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "pocketproducts", "cod='mioCodTest'");
    }
}