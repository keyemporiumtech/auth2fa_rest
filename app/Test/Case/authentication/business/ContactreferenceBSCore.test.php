<?php
App::uses("ContactreferenceBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ContactreferenceBSCoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new ContactreferenceBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "contactreferences");
            $bs = new ContactreferenceBS();
            $obj = $bs->instance();
            $obj['Contactreference']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "contactreferences", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "contactreferences", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "contactreferences", "cod='mioCodTest'");
        }
    }

    public function testUnique() {
        $autoIncrement = $this->addRecord();
        $bs = new ContactreferenceBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Contactreference']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testAll() {
        $autoIncrement = $this->addRecord();
        $bs = new ContactreferenceBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Contactreference']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "contactreferences");

        // obj
        $bs = new ContactreferenceBS();
        $obj = $bs->instance();
        $obj['Contactreference']['cod'] = "mioCodTest";

        // save
        $bs = new ContactreferenceBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM contactreferences WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['contactreferences'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "contactreferences", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "contactreferences", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "contactreferences", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new ContactreferenceBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Contactreference']['cod'] = "OthermioCodTest";

        // edit
        $bs = new ContactreferenceBS();
        $id = $bs->save($objNew);

        // test
        $bs = new ContactreferenceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Contactreference']['cod'], 'OthermioCodTest');

        // reset
        $bs = new ContactreferenceBS();
        $id = $bs->save($obj);

        // test
        $bs = new ContactreferenceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Contactreference']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "contactreferences");

        // insert
        $sql = "INSERT INTO contactreferences (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM contactreferences WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['contactreferences'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new ContactreferenceBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "contactreferences", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "contactreferences", "cod='mioCodTest'");
    }
}