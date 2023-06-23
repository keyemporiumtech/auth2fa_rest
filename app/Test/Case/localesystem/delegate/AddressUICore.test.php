<?php
App::uses("AddressUI", "modules/localesystem/delegate");
App::uses("AddressBS", "modules/localesystem/business");
App::uses("MysqlUtilityTest", "Test/utility");

class AddressUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new AddressBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "addresses");
            $bs = new AddressBS();
            $obj = $bs->instance();
            $obj['Address']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "addresses", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "addresses", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "addresses", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new AddressUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Address']['id'], 1);
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
        $ui = new AddressUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Address']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "addresses");

        // obj
        $bs = new AddressBS();
        $obj = $bs->instance();
        $obj['Address']['cod'] = "mioCodTest";

        // save
        $ui = new AddressUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM addresses WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['addresses'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "addresses", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "addresses", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "addresses", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new AddressBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Address']['cod'] = "OthermioCodTest";

        // edit
        $ui = new AddressUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new AddressBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Address']['cod'], 'OthermioCodTest');

        // reset
        $ui = new AddressUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new AddressBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Address']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "addresses");

        // insert
        $sql = "INSERT INTO addresses (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM addresses WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['addresses'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new AddressUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "addresses", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "addresses", "cod='mioCodTest'");
    }
}