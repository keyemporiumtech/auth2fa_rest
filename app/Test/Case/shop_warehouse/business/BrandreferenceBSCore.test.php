<?php
App::uses("BrandreferenceBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class BrandreferenceUICoreTest extends CakeTestCase {

    public function testUnique() {
        $bs = new BrandreferenceBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Brandreference']['id'], 1);
    }

    public function testAll() {
        $bs = new BrandreferenceBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Brandreference']['id'], 1);
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
        $bs = new BrandreferenceBS();
        $id = $bs->save($obj);

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
        $bs = new BrandreferenceBS();
        $id = $bs->save($objNew);

        // test
        $bs = new BrandreferenceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Brandreference']['cod'], 'OthermioCodTest');

        // reset
        $bs = new BrandreferenceBS();
        $id = $bs->save($obj);

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
        $bs = new BrandreferenceBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "brandreferences", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "brandreferences", "cod='mioCodTest'");
    }
}