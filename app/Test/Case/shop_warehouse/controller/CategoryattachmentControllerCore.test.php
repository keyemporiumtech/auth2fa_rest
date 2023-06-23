<?php
App::uses("CategoryattachmentBS", "modules/shop_warehouse/business");
App::uses("Codes", "Config/system");
App::uses("MysqlUtilityTest", "Test/utility");

class CategoryattachmentControllerCoreTest extends ControllerTestCase {

    public function testGet() {
        $data = array(
            'id_categoryattachment' => 1,
        );
        $post1 = $this->testAction('categoryattachment/get', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $responseData = json_decode($post1, true);
        $this->assertEquals($responseData['id'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        $data = array(
            'id_categoryattachment' => 9999999,
        );
        $post1 = $this->testAction('categoryattachment/get', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));
        $responseData = json_decode($post1, true);
        $this->assertEquals($responseData, null);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], -1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 500);
        $this->assertEquals($this->controller->response->header('_headers', '')['exceptioncod'], Codes::get("EXCEPTION_GENERIC"));
    }

    public function testTable() {
        // CONDITIONS
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 99999;
        $conditions = array(
            $condition,
        );

        $data = array(
            'filters' => json_encode($conditions),
        );
        $post2 = $this->testAction('categoryattachment/table', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $paginator = json_decode($post2, true);
        $this->assertEquals(count($paginator['list']), 0);
        $this->assertEquals($paginator['count'], 0);
        $this->assertEquals($paginator['pages'], 0);
    }

    public function testPaginate() {
        $bs = new CategoryattachmentBS();
        $sql = "SELECT COUNT(*) as num FROM categoryattachments";
        $num = $bs->queryCount($sql, "num");

        $autoIncrement = null;
        $dbo = null;
        if ($num == 1) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "categoryattachments");

            // insert
            $sql = "INSERT INTO categoryattachments (id,cod,created) VALUES";
            $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
            $data = $dbo->query($sql);
        } elseif ($num == 0) {
            $this->assertEquals($num, 0);
        } else {

            // pagination
            $paginate = new DBPaginate();
            $paginate->limit = 1;
            $paginate->page = 2;

            $data = array(
                'paginate' => json_encode($paginate),
            );
            $post1 = $this->testAction('categoryattachment/table', array(
                'data' => $data,
                'return' => 'view',
                'method' => 'POST',
            ));

            $paginator = json_decode($post1, true);
            $this->assertEquals($paginator['list'][0]['id'] > 1, true);
            $this->assertEquals(count($paginator['list']), 1);
            $this->assertEquals($paginator['count'], $num);
            $this->assertEquals($paginator['pages'], $num);
        }
        if (!empty($autoIncrement)) {
            // search
            $search = "SELECT * FROM categoryattachments WHERE cod='mioCodTest'";
            $data = $dbo->query($search);
            $result = $data[0]['categoryattachments'];
            $id = $result['id'];
            $this->assertEquals(!empty($data), true);
            $this->assertEquals($result['cod'], 'mioCodTest');

            // delete
            $bs = new CategoryattachmentBS();
            $id = $bs->delete($id);

            // reset
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "categoryattachments", $autoIncrement);

            // verify reset
            MysqlUtilityTest::verifyDeleted($dbo, $this, "categoryattachments", "cod='mioCodTest'");
        }
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "categoryattachments");

        // obj
        $bs = new CategoryattachmentBS();
        $obj = $bs->instance();
        $obj['Categoryattachment']['cod'] = "mioCodTest";

        // save
        $data = array(
            'categoryattachment' => json_encode($obj['Categoryattachment']),
        );
        $post1 = $this->testAction('categoryattachment/save', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // search
        $search = "SELECT * FROM categoryattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['categoryattachments'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "categoryattachments", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "categoryattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "categoryattachments", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new CategoryattachmentBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Categoryattachment']['cod'] = "AltramioCodTest";

        // edit
        $data = array(
            'id_categoryattachment' => $objNew['Categoryattachment']['id'],
            'categoryattachment' => json_encode($objNew['Categoryattachment']),
        );
        $post1 = $this->testAction('categoryattachment/edit', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // test
        $bs = new CategoryattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Categoryattachment']['cod'], 'AltramioCodTest');

        // reset
        $ui = new CategoryattachmentUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new CategoryattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Categoryattachment']['cod'] == 'AltramioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "categoryattachments");

        // insert
        $sql = "INSERT INTO categoryattachments (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM categoryattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['categoryattachments'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $data = array(
            'id_categoryattachment' => $id,
        );
        $post1 = $this->testAction('categoryattachment/delete', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "categoryattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "categoryattachments", "cod='mioCodTest'");
    }
}