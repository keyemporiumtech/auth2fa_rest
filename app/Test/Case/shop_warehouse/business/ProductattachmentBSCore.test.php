<?php
App::uses("ProductattachmentBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ProductattachmentUICoreTest extends CakeTestCase {

    public function testUnique() {
        $bs = new ProductattachmentBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Productattachment']['id'], 1);
    }

    public function testAll() {
        $bs = new ProductattachmentBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Productattachment']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "productattachments");

        // obj
        $bs = new ProductattachmentBS();
        $obj = $bs->instance();
        $obj['Productattachment']['cod'] = "mioCodTest";

        // save
        $bs = new ProductattachmentBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM productattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['productattachments'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "productattachments", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "productattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "productattachments", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new ProductattachmentBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Productattachment']['cod'] = "OthermioCodTest";

        // edit
        $bs = new ProductattachmentBS();
        $id = $bs->save($objNew);

        // test
        $bs = new ProductattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Productattachment']['cod'], 'OthermioCodTest');

        // reset
        $bs = new ProductattachmentBS();
        $id = $bs->save($obj);

        // test
        $bs = new ProductattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Productattachment']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "productattachments");

        // insert
        $sql = "INSERT INTO productattachments (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM productattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['productattachments'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new ProductattachmentBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "productattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "productattachments", "cod='mioCodTest'");
    }
}