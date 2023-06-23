<?php
App::uses("ProducttaxBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ProducttaxUICoreTest extends CakeTestCase {

    public function testUnique() {
        $bs = new ProducttaxBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Producttax']['id'], 1);
    }

    public function testAll() {
        $bs = new ProducttaxBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Producttax']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "producttaxs");

        // obj
        $bs = new ProducttaxBS();
        $obj = $bs->instance();
        $obj['Producttax']['cod'] = "mioCodTest";

        // save
        $bs = new ProducttaxBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM producttaxs WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['producttaxs'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "producttaxs", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "producttaxs", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "producttaxs", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new ProducttaxBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Producttax']['cod'] = "OthermioCodTest";

        // edit
        $bs = new ProducttaxBS();
        $id = $bs->save($objNew);

        // test
        $bs = new ProducttaxBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Producttax']['cod'], 'OthermioCodTest');

        // reset
        $bs = new ProducttaxBS();
        $id = $bs->save($obj);

        // test
        $bs = new ProducttaxBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Producttax']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "producttaxs");

        // insert
        $sql = "INSERT INTO producttaxs (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM producttaxs WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['producttaxs'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new ProducttaxBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "producttaxs", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "producttaxs", "cod='mioCodTest'");
    }
}