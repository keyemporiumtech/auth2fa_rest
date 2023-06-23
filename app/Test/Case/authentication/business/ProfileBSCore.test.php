<?php
App::uses("ProfileBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ProfileBSCoreTest extends CakeTestCase {

    function addRecord() {
        $bs = new ProfileBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "profiles");
            $bs = new ProfileBS();
            $obj = $bs->instance();
            $obj['Profile']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "profiles", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "profiles", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "profiles", "cod='mioCodTest'");
        }
    }

    function testUnique() {
        $autoIncrement = $this->addRecord();
        $bs = new ProfileBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Profile']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    function testAll() {
        $autoIncrement = $this->addRecord();
        $bs = new ProfileBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Profile']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "profiles");

        // obj
        $bs = new ProfileBS();
        $obj = $bs->instance();
        $obj['Profile']['cod'] = "mioCodTest";

        // save
        $bs = new ProfileBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM profiles WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['profiles'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "profiles", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "profiles", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "profiles", "cod='mioCodTest'");
    }

    function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new ProfileBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Profile']['cod'] = "OthermioCodTest";

        // edit
        $bs = new ProfileBS();
        $id = $bs->save($objNew);

        // test
        $bs = new ProfileBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Profile']['cod'], 'OthermioCodTest');

        // reset
        $bs = new ProfileBS();
        $id = $bs->save($obj);

        // test
        $bs = new ProfileBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Profile']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "profiles");

        // insert
        $sql = "INSERT INTO profiles (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM profiles WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['profiles'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new ProfileBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "profiles", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "profiles", "cod='mioCodTest'");
    }
}