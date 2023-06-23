<?php
App::uses("ServiceattachmentBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ServiceattachmentUICoreTest extends CakeTestCase {

    public function testUnique() {
        $bs = new ServiceattachmentBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Serviceattachment']['id'], 1);
    }

    public function testAll() {
        $bs = new ServiceattachmentBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Serviceattachment']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "serviceattachments");

        // obj
        $bs = new ServiceattachmentBS();
        $obj = $bs->instance();
        $obj['Serviceattachment']['cod'] = "mioCodTest";

        // save
        $bs = new ServiceattachmentBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM serviceattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['serviceattachments'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "serviceattachments", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "serviceattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "serviceattachments", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new ServiceattachmentBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Serviceattachment']['cod'] = "OthermioCodTest";

        // edit
        $bs = new ServiceattachmentBS();
        $id = $bs->save($objNew);

        // test
        $bs = new ServiceattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Serviceattachment']['cod'], 'OthermioCodTest');

        // reset
        $bs = new ServiceattachmentBS();
        $id = $bs->save($obj);

        // test
        $bs = new ServiceattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Serviceattachment']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "serviceattachments");

        // insert
        $sql = "INSERT INTO serviceattachments (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM serviceattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['serviceattachments'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new ServiceattachmentBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "serviceattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "serviceattachments", "cod='mioCodTest'");
    }
}