<?php
App::uses("PocketdiscountBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class PocketdiscountBSCoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new PocketdiscountBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pocketdiscounts");
            $bs = new PocketdiscountBS();
            $obj = $bs->instance();
            $obj['Pocketdiscount']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "pocketdiscounts", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pocketdiscounts", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "pocketdiscounts", "cod='mioCodTest'");
        }
    }

    public function testUnique() {
        $autoIncrement = $this->addRecord();
        $bs = new PocketdiscountBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Pocketdiscount']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testAll() {
        $autoIncrement = $this->addRecord();
        $bs = new PocketdiscountBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Pocketdiscount']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pocketdiscounts");

        // obj
        $bs = new PocketdiscountBS();
        $obj = $bs->instance();
        $obj['Pocketdiscount']['cod'] = "mioCodTest";

        // save
        $bs = new PocketdiscountBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM pocketdiscounts WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['pocketdiscounts'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "pocketdiscounts", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pocketdiscounts", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "pocketdiscounts", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new PocketdiscountBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Pocketdiscount']['cod'] = "OthermioCodTest";

        // edit
        $bs = new PocketdiscountBS();
        $id = $bs->save($objNew);

        // test
        $bs = new PocketdiscountBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Pocketdiscount']['cod'], 'OthermioCodTest');

        // reset
        $bs = new PocketdiscountBS();
        $id = $bs->save($obj);

        // test
        $bs = new PocketdiscountBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Pocketdiscount']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pocketdiscounts");

        // insert
        $sql = "INSERT INTO pocketdiscounts (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM pocketdiscounts WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['pocketdiscounts'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new PocketdiscountBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pocketdiscounts", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "pocketdiscounts", "cod='mioCodTest'");
    }
}