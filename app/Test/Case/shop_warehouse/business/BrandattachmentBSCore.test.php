<?php
App::uses("BrandattachmentBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class BrandattachmentUICoreTest extends CakeTestCase {

    public function addRecord() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "brandattachments");
        $bs = new BrandattachmentBS();
        $obj = $bs->instance();
        $obj['Brandattachment']['cod'] = "mioCodTest";
        $bs->save($obj);
        return $autoIncrement;
    }

    public function removeRecord($dbo, $autoIncrement) {
        MysqlUtilityTest::deleteLast($dbo, "brandattachments", "cod='mioCodTest'");
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "brandattachments", $autoIncrement);
        MysqlUtilityTest::verifyDeleted($dbo, $this, "brandattachments", "cod='mioCodTest'");
    }

    public function testUnique() {
        $bs = new BrandattachmentBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Brandattachment']['id'], 1);
    }

    public function testAll() {
        $bs = new BrandattachmentBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Brandattachment']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "brandattachments");

        // obj
        $bs = new BrandattachmentBS();
        $obj = $bs->instance();
        $obj['Brandattachment']['cod'] = "mioCodTest";

        // save
        $bs = new BrandattachmentBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM brandattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['brandattachments'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "brandattachments", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "brandattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "brandattachments", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new BrandattachmentBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Brandattachment']['cod'] = "OthermioCodTest";

        // edit
        $bs = new BrandattachmentBS();
        $id = $bs->save($objNew);

        // test
        $bs = new BrandattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Brandattachment']['cod'], 'OthermioCodTest');

        // reset
        $bs = new BrandattachmentBS();
        $id = $bs->save($obj);

        // test
        $bs = new BrandattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Brandattachment']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "brandattachments");

        // insert
        $sql = "INSERT INTO brandattachments (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM brandattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['brandattachments'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new BrandattachmentBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "brandattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "brandattachments", "cod='mioCodTest'");
    }
}