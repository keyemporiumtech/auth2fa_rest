<?php
App::uses("GroupBS", "modules/cakeutils/business");
App::uses("MysqlUtilityTest", "Test/utility");

class GroupBSCoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new GroupBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "groups");
            $bs = new GroupBS();
            $obj = $bs->instance();
            $obj['Group']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "groups", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "groups", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "groups", "cod='mioCodTest'");
        }
    }

    public function testUnique() {
        $autoIncrement = $this->addRecord();
        $bs = new GroupBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Group']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testAll() {
        $autoIncrement = $this->addRecord();
        $bs = new GroupBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Group']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "groups");

        // obj
        $bs = new GroupBS();
        $obj = $bs->instance();
        $obj['Group']['cod'] = "mioCodTest";

        // save
        $bs = new GroupBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM groups WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['groups'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "groups", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "groups", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "groups", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new GroupBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Group']['cod'] = "OthermioCodTest";

        // edit
        $bs = new GroupBS();
        $id = $bs->save($objNew);

        // test
        $bs = new GroupBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Group']['cod'], 'OthermioCodTest');

        // reset
        $bs = new GroupBS();
        $id = $bs->save($obj);

        // test
        $bs = new GroupBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Group']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "groups");

        // insert
        $sql = "INSERT INTO groups (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM groups WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['groups'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new GroupBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "groups", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "groups", "cod='mioCodTest'");
    }
}