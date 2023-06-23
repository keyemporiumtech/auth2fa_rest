<?php
App::uses("PockettaxUI", "modules/shop_warehouse/delegate");
App::uses("PockettaxBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class PockettaxUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new PockettaxBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pockettaxes");
            $bs = new PockettaxBS();
            $obj = $bs->instance();
            $obj['Pockettax']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "pockettaxes", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pockettaxes", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "pockettaxes", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new PockettaxUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Pockettax']['id'], 1);
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
        $ui = new PockettaxUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Pockettax']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pockettaxes");

        // obj
        $bs = new PockettaxBS();
        $obj = $bs->instance();
        $obj['Pockettax']['cod'] = "mioCodTest";

        // save
        $ui = new PockettaxUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM pockettaxes WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['pockettaxes'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "pockettaxes", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pockettaxes", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "pockettaxes", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new PockettaxBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Pockettax']['cod'] = "OthermioCodTest";

        // edit
        $ui = new PockettaxUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new PockettaxBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Pockettax']['cod'], 'OthermioCodTest');

        // reset
        $ui = new PockettaxUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new PockettaxBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Pockettax']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pockettaxes");

        // insert
        $sql = "INSERT INTO pockettaxes (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM pockettaxes WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['pockettaxes'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new PockettaxUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pockettaxes", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "pockettaxes", "cod='mioCodTest'");
    }
}