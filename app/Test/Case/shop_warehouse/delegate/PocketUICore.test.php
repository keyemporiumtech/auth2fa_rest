<?php
App::uses("PocketUI", "modules/shop_warehouse/delegate");
App::uses("PocketBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class PocketUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new PocketBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pockets");
            $bs = new PocketBS();
            $obj = $bs->instance();
            $obj['Pocket']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "pockets", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pockets", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "pockets", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new PocketUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Pocket']['id'], 1);
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
        $ui = new PocketUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Pocket']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pockets");

        // obj
        $bs = new PocketBS();
        $obj = $bs->instance();
        $obj['Pocket']['cod'] = "mioCodTest";

        // save
        $ui = new PocketUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM pockets WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['pockets'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "pockets", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pockets", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "pockets", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new PocketBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Pocket']['cod'] = "OthermioCodTest";

        // edit
        $ui = new PocketUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new PocketBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Pocket']['cod'], 'OthermioCodTest');

        // reset
        $ui = new PocketUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new PocketBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Pocket']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pockets");

        // insert
        $sql = "INSERT INTO pockets (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM pockets WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['pockets'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new PocketUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pockets", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "pockets", "cod='mioCodTest'");
    }
}