<?php
App::uses("ClienttokenBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ClienttokenBSCoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new ClienttokenBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "clienttokens");
            $bs = new ClienttokenBS();
            $obj = $bs->instance();
            $obj['Clienttoken']['appname'] = "mioAppnameTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "clienttokens", "appname='mioAppnameTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "clienttokens", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "clienttokens", "appname='mioAppnameTest'");
        }
    }

    public function testUnique() {
        $autoIncrement = $this->addRecord();
        $bs = new ClienttokenBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Clienttoken']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testAll() {
        $autoIncrement = $this->addRecord();
        $bs = new ClienttokenBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Clienttoken']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "clienttokens");

        // obj
        $bs = new ClienttokenBS();
        $obj = $bs->instance();
        $obj['Clienttoken']['appname'] = "mioAppnameTest";

        // save
        $bs = new ClienttokenBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM clienttokens WHERE appname='mioAppnameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['clienttokens'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['appname'], 'mioAppnameTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "clienttokens", "appname='mioAppnameTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "clienttokens", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "clienttokens", "appname='mioAppnameTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new ClienttokenBS();
        $bs->addPropertyDao("flgDecrypt", true);
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Clienttoken']['appname'] = "OthermioAppnameTest";

        // edit
        $bs = new ClienttokenBS();
        $id = $bs->save($objNew);

        // test
        $bs = new ClienttokenBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Clienttoken']['appname'], 'OthermioAppnameTest');

        // reset
        $bs = new ClienttokenBS();
        $id = $bs->save($obj);

        // test
        $bs = new ClienttokenBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Clienttoken']['appname'] == 'OthermioAppnameTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "clienttokens");

        // insert
        $sql = "INSERT INTO clienttokens (id,appname,created) VALUES";
        $sql .= " (NULL, 'mioAppnameTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM clienttokens WHERE appname='mioAppnameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['clienttokens'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['appname'], 'mioAppnameTest');

        // delete
        $bs = new ClienttokenBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "clienttokens", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "clienttokens", "appname='mioAppnameTest'");
    }
}