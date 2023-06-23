<?php
App::uses("CategoryUI", "modules/shop_warehouse/delegate");
App::uses("CategoryBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class CategoryUICoreTest extends CakeTestCase {

    public function testGet() {
        $ui = new CategoryUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Category']['id'], 1);
    }

    public function testTable() {
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 1;
        $conditions = array(
            $condition,
        );
        $ui = new CategoryUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Category']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "categorys");

        // obj
        $bs = new CategoryBS();
        $obj = $bs->instance();
        $obj['Category']['cod'] = "mioCodTest";

        // save
        $ui = new CategoryUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM categorys WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['categorys'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "categorys", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "categorys", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "categorys", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new CategoryBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Category']['cod'] = "OthermioCodTest";

        // edit
        $ui = new CategoryUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new CategoryBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Category']['cod'], 'OthermioCodTest');

        // reset
        $ui = new CategoryUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new CategoryBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Category']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "categorys");

        // insert
        $sql = "INSERT INTO categorys (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM categorys WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['categorys'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new CategoryUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "categorys", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "categorys", "cod='mioCodTest'");
    }
}