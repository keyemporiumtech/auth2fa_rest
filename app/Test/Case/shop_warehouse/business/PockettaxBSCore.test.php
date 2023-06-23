<?php
App::uses("PockettaxBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class PockettaxBSCoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new PockettaxBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pockettaxes");
            $bs = new PockettaxBS();
            $obj = $bs->instance();
            $obj['Pockettax']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "pockettaxes", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pockettaxes", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "pockettaxes", "cod='mioCodTest'");
        }
    }

    public function testUnique() {
        $autoIncrement = $this->addRecord();
        $bs = new PockettaxBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Pockettax']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testAll() {
        $autoIncrement = $this->addRecord();
        $bs = new PockettaxBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Pockettax']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pockettaxes");

        // obj
        $bs = new PockettaxBS();
        $obj = $bs->instance();
        $obj['Pockettax']['cod'] = "mioCodTest";

        // save
        $bs = new PockettaxBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM pockettaxes WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['pockettaxes'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "pockettaxes", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pockettaxes", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "pockettaxes", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new PockettaxBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Pockettax']['cod'] = "OthermioCodTest";

        // edit
        $bs = new PockettaxBS();
        $id = $bs->save($objNew);

        // test
        $bs = new PockettaxBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Pockettax']['cod'], 'OthermioCodTest');

        // reset
        $bs = new PockettaxBS();
        $id = $bs->save($obj);

        // test
        $bs = new PockettaxBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Pockettax']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pockettaxes");

        // insert
        $sql = "INSERT INTO pockettaxes (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM pockettaxes WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['pockettaxes'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new PockettaxBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pockettaxes", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "pockettaxes", "cod='mioCodTest'");
    }
}