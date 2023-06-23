<?php
App::uses("ProfessionattachmentUI", "modules/work_cv/delegate");
App::uses("ProfessionattachmentBS", "modules/work_cv/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ProfessionattachmentUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new ProfessionattachmentBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "professionattachments");
            $bs = new ProfessionattachmentBS();
            $obj = $bs->instance();
            $obj['Professionattachment']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "professionattachments", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "professionattachments", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "professionattachments", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new ProfessionattachmentUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Professionattachment']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testTable() {
        $autoIncrement = $this->addRecord();
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 1;
        $conditions = array(
            $condition,
        );
        $ui = new ProfessionattachmentUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Professionattachment']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "professionattachments");

        // obj
        $bs = new ProfessionattachmentBS();
        $obj = $bs->instance();
        $obj['Professionattachment']['cod'] = "mioCodTest";

        // save
        $ui = new ProfessionattachmentUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM professionattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['professionattachments'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "professionattachments", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "professionattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "professionattachments", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new ProfessionattachmentBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Professionattachment']['cod'] = "OthermioCodTest";

        // edit
        $ui = new ProfessionattachmentUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new ProfessionattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Professionattachment']['cod'], 'OthermioCodTest');

        // reset
        $ui = new ProfessionattachmentUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new ProfessionattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Professionattachment']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "professionattachments");

        // insert
        $sql = "INSERT INTO professionattachments (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM professionattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['professionattachments'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new ProfessionattachmentUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "professionattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "professionattachments", "cod='mioCodTest'");
    }
}