<?php
App::uses("UserreferenceBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class UserreferenceBSCoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new UserreferenceBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "userreferences");
            $bs = new UserreferenceBS();
            $obj = $bs->instance();
            $obj['Userreference']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "userreferences", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userreferences", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "userreferences", "cod='mioCodTest'");
        }
    }

    public function testUnique() {
        $autoIncrement = $this->addRecord();
        $bs = new UserreferenceBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Userreference']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testAll() {
        $autoIncrement = $this->addRecord();
        $bs = new UserreferenceBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Userreference']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "userreferences");

        // obj
        $bs = new UserreferenceBS();
        $obj = $bs->instance();
        $obj['Userreference']['cod'] = "mioCodTest";

        // save
        $bs = new UserreferenceBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM userreferences WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['userreferences'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "userreferences", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userreferences", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "userreferences", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new UserreferenceBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Userreference']['cod'] = "OthermioCodTest";

        // edit
        $bs = new UserreferenceBS();
        $id = $bs->save($objNew);

        // test
        $bs = new UserreferenceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Userreference']['cod'], 'OthermioCodTest');

        // reset
        $bs = new UserreferenceBS();
        $id = $bs->save($obj);

        // test
        $bs = new UserreferenceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Userreference']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "userreferences");

        // insert
        $sql = "INSERT INTO userreferences (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM userreferences WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['userreferences'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new UserreferenceBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userreferences", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "userreferences", "cod='mioCodTest'");
    }
}