<?php
App::uses("PocketserviceBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class PocketserviceBSCoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new PocketserviceBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pocketservices");
            $bs = new PocketserviceBS();
            $obj = $bs->instance();
            $obj['Pocketservice']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "pocketservices", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pocketservices", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "pocketservices", "cod='mioCodTest'");
        }
    }

    public function testUnique() {
        $autoIncrement = $this->addRecord();
        $bs = new PocketserviceBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Pocketservice']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testAll() {
        $autoIncrement = $this->addRecord();
        $bs = new PocketserviceBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Pocketservice']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pocketservices");

        // obj
        $bs = new PocketserviceBS();
        $obj = $bs->instance();
        $obj['Pocketservice']['cod'] = "mioCodTest";

        // save
        $bs = new PocketserviceBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM pocketservices WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['pocketservices'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "pocketservices", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pocketservices", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "pocketservices", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new PocketserviceBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Pocketservice']['cod'] = "OthermioCodTest";

        // edit
        $bs = new PocketserviceBS();
        $id = $bs->save($objNew);

        // test
        $bs = new PocketserviceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Pocketservice']['cod'], 'OthermioCodTest');

        // reset
        $bs = new PocketserviceBS();
        $id = $bs->save($obj);

        // test
        $bs = new PocketserviceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Pocketservice']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pocketservices");

        // insert
        $sql = "INSERT INTO pocketservices (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM pocketservices WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['pocketservices'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new PocketserviceBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pocketservices", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "pocketservices", "cod='mioCodTest'");
    }
}