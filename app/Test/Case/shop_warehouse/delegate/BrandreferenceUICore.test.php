<?php
App::uses("BrandreferenceUI", "modules/shop_warehouse/delegate");
App::uses("BrandreferenceBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class BrandreferenceUICoreTest extends CakeTestCase {

    public function testGet() {
        $ui = new BrandreferenceUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Brandreference']['id'], 1);
    }

    public function testTable() {
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 1;
        $conditions = array(
            $condition,
        );
        $ui = new BrandreferenceUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Brandreference']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "brandreferences");

        // obj
        $bs = new BrandreferenceBS();
        $obj = $bs->instance();
        $obj['Brandreference']['cod'] = "mioCodTest";

        // save
        $ui = new BrandreferenceUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM brandreferences WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['brandreferences'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "brandreferences", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "brandreferences", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "brandreferences", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new BrandreferenceBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Brandreference']['cod'] = "OthermioCodTest";

        // edit
        $ui = new BrandreferenceUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new BrandreferenceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Brandreference']['cod'], 'OthermioCodTest');

        // reset
        $ui = new BrandreferenceUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new BrandreferenceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Brandreference']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "brandreferences");

        // insert
        $sql = "INSERT INTO brandreferences (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM brandreferences WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['brandreferences'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new BrandreferenceUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "brandreferences", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "brandreferences", "cod='mioCodTest'");
    }
}