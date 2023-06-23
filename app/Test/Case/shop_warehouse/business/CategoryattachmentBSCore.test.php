<?php
App::uses("CategoryattachmentBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class CategoryattachmentUICoreTest extends CakeTestCase {

    public function testUnique() {
        $bs = new CategoryattachmentBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Categoryattachment']['id'], 1);
    }

    public function testAll() {
        $bs = new CategoryattachmentBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Categoryattachment']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "categoryattachments");

        // obj
        $bs = new CategoryattachmentBS();
        $obj = $bs->instance();
        $obj['Categoryattachment']['cod'] = "mioCodTest";

        // save
        $bs = new CategoryattachmentBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM categoryattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['categoryattachments'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "categoryattachments", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "categoryattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "categoryattachments", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new CategoryattachmentBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Categoryattachment']['cod'] = "OthermioCodTest";

        // edit
        $bs = new CategoryattachmentBS();
        $id = $bs->save($objNew);

        // test
        $bs = new CategoryattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Categoryattachment']['cod'], 'OthermioCodTest');

        // reset
        $bs = new CategoryattachmentBS();
        $id = $bs->save($obj);

        // test
        $bs = new CategoryattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Categoryattachment']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "categoryattachments");

        // insert
        $sql = "INSERT INTO categoryattachments (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM categoryattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['categoryattachments'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new CategoryattachmentBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "categoryattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "categoryattachments", "cod='mioCodTest'");
    }
}