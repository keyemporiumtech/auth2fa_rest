<?php
App::uses("CategoryBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class CategoryUICoreTest extends CakeTestCase {

    public function testUnique() {
        $bs = new CategoryBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Category']['id'], 1);
    }

    public function testAll() {
        $bs = new CategoryBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Category']['id'], 1);
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
        $bs = new CategoryBS();
        $id = $bs->save($obj);

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
        $bs = new CategoryBS();
        $id = $bs->save($objNew);

        // test
        $bs = new CategoryBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Category']['cod'], 'OthermioCodTest');

        // reset
        $bs = new CategoryBS();
        $id = $bs->save($obj);

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
        $bs = new CategoryBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "categorys", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "categorys", "cod='mioCodTest'");
    }
}