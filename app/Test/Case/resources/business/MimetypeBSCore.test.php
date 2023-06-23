<?php
App::uses("MimetypeBS", "modules/resources/business");
App::uses("MysqlUtilityTest", "Test/utility");

class MimetypeBSCoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new MimetypeBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mimetypes");
            $bs = new MimetypeBS();
            $obj = $bs->instance();
            $obj['Mimetype']['value'] = "mioValueTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "mimetypes", "value='mioValueTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mimetypes", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "mimetypes", "value='mioValueTest'");
        }
    }

    public function testUnique() {
        $autoIncrement = $this->addRecord();
        $bs = new MimetypeBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Mimetype']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testAll() {
        $autoIncrement = $this->addRecord();
        $bs = new MimetypeBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Mimetype']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mimetypes");

        // obj
        $bs = new MimetypeBS();
        $obj = $bs->instance();
        $obj['Mimetype']['value'] = "mioValueTest";

        // save
        $bs = new MimetypeBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM mimetypes WHERE value='mioValueTest'";
        $data = $dbo->query($search);
        $result = $data[0]['mimetypes'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['value'], 'mioValueTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "mimetypes", "value='mioValueTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mimetypes", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "mimetypes", "value='mioValueTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new MimetypeBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Mimetype']['value'] = "OthermioValueTest";

        // edit
        $bs = new MimetypeBS();
        $id = $bs->save($objNew);

        // test
        $bs = new MimetypeBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Mimetype']['value'], 'OthermioValueTest');

        // reset
        $bs = new MimetypeBS();
        $id = $bs->save($obj);

        // test
        $bs = new MimetypeBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Mimetype']['value'] == 'OthermioValueTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mimetypes");

        // insert
        $sql = "INSERT INTO mimetypes (id,value,created) VALUES";
        $sql .= " (NULL, 'mioValueTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM mimetypes WHERE value='mioValueTest'";
        $data = $dbo->query($search);
        $result = $data[0]['mimetypes'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['value'], 'mioValueTest');

        // delete
        $bs = new MimetypeBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mimetypes", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "mimetypes", "value='mioValueTest'");
    }
}