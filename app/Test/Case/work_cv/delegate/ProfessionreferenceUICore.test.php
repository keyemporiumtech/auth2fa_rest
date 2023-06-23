<?php
App::uses("ProfessionreferenceUI", "modules/work_cv/delegate");
App::uses("ProfessionreferenceBS", "modules/work_cv/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ProfessionreferenceUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new ProfessionreferenceBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "professionreferences");
            $bs = new ProfessionreferenceBS();
            $obj = $bs->instance();
            $obj['Professionreference']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "professionreferences", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "professionreferences", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "professionreferences", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new ProfessionreferenceUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Professionreference']['id'], 1);
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
        $ui = new ProfessionreferenceUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Professionreference']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "professionreferences");

        // obj
        $bs = new ProfessionreferenceBS();
        $obj = $bs->instance();
        $obj['Professionreference']['cod'] = "mioCodTest";

        // save
        $ui = new ProfessionreferenceUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM professionreferences WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['professionreferences'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "professionreferences", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "professionreferences", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "professionreferences", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new ProfessionreferenceBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Professionreference']['cod'] = "OthermioCodTest";

        // edit
        $ui = new ProfessionreferenceUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new ProfessionreferenceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Professionreference']['cod'], 'OthermioCodTest');

        // reset
        $ui = new ProfessionreferenceUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new ProfessionreferenceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Professionreference']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "professionreferences");

        // insert
        $sql = "INSERT INTO professionreferences (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM professionreferences WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['professionreferences'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new ProfessionreferenceUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "professionreferences", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "professionreferences", "cod='mioCodTest'");
    }
}