<?php
App::uses("MysqlUtilityTest", "Test/utility");
App::uses("UseraddressUI", "modules/authentication/delegate");
App::uses("UseraddressBS", "modules/authentication/business");
App::uses("UserreferenceUI", "modules/authentication/delegate");
App::uses("UserreferenceBS", "modules/authentication/business");
App::uses("UserattachmentUI", "modules/authentication/delegate");
App::uses("UserattachmentBS", "modules/authentication/business");
App::uses("AddressBS", "modules/localesystem/business");
App::uses("ContactreferenceBS", "modules/authentication/business");
App::uses("AttachmentBS", "modules/resources/business");

class UserRelationsPrincipalTest extends CakeTestCase {

    public function testUseraddressPrincipal() {
        $ui = new UseraddressUI();
        $principal = $ui->getPrincipal(null, 'test1@gmail.com');
        $this->assertEquals($principal['Useraddress']['flgprincipal'], 1);

        // aggiungo un nuovo indirizzo
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrementAddress = MysqlUtilityTest::getAutoIncrement($dbo, "addresses");
        $autoIncrementUseraddress = MysqlUtilityTest::getAutoIncrement($dbo, "useraddresses");

        // obj
        $bs = new AddressBS();
        $obj = $bs->instance();
        $obj['Address']['street'] = "StradaTest";
        $id = $bs->save($obj);

        $uabs = new UseraddressBS();
        $ua = $uabs->instance();
        $ua['Useraddress']['cod'] = "MioCodTest";
        $ua['Useraddress']['user'] = $principal['Useraddress']['user'];
        $ua['Useraddress']['address'] = $id;
        $idua = $uabs->save($ua);
        $this->assertEquals($principal['Useraddress']['flgprincipal'], 1);
        $this->assertEquals($ua['Useraddress']['flgprincipal'], 0);

        // cambio principal
        $ui = new UseraddressUI();
        $ui->setPrincipal(null, 'test1@gmail.com', null, 'MioCodTest');

        $uabs = new UseraddressBS();
        $first = $uabs->unique($principal['Useraddress']['id']);
        $uabs = new UseraddressBS();
        $second = $uabs->unique($idua);
        $this->assertEquals($first['Useraddress']['flgprincipal'], 0);
        $this->assertEquals($second['Useraddress']['flgprincipal'], 1);

        // resetto principal
        $ui = new UseraddressUI();
        $ui->setPrincipal(null, 'test1@gmail.com', null, $principal['Useraddress']['cod']);

        $uabs = new UseraddressBS();
        $first = $uabs->unique($principal['Useraddress']['id']);
        $uabs = new UseraddressBS();
        $second = $uabs->unique($idua);
        $this->assertEquals($first['Useraddress']['flgprincipal'], 1);
        $this->assertEquals($second['Useraddress']['flgprincipal'], 0);

        // delete
        MysqlUtilityTest::deleteLast($dbo, "addresses", "street='StradaTest'");
        MysqlUtilityTest::deleteLast($dbo, "useraddresses", "cod='MioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "addresses", $autoIncrementAddress);
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "useraddresses", $autoIncrementUseraddress);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "addresses", "street='StradaTest'");
        MysqlUtilityTest::verifyDeleted($dbo, $this, "useraddresses", "cod='MioCodTest'");
    }

    public function testUserreferencePrincipal() {
        $ui = new UserreferenceUI();
        $principal = $ui->getPrincipal(null, 'test1@gmail.com', 4);
        $this->assertEquals($principal['Userreference']['flgprincipal'], 1);

        // aggiungo un nuovo contatto
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrementReference = MysqlUtilityTest::getAutoIncrement($dbo, "contactreferences");
        $autoIncrementUserreference = MysqlUtilityTest::getAutoIncrement($dbo, "userreferences");

        // obj
        $bs = new ContactreferenceBS();
        $obj = $bs->instance();
        $obj['Contactreference']['val'] = "prova@prova.it";
        $id = $bs->save($obj);

        $uabs = new UserreferenceBS();
        $ua = $uabs->instance();
        $ua['Userreference']['user'] = $principal['Userreference']['user'];
        $ua['Userreference']['contactreference'] = $id;
        $ua['Userreference']['tpcontactreference'] = 4;
        $ua['Userreference']['cod'] = 'MioCodTest';
        $idua = $uabs->save($ua);
        $this->assertEquals($principal['Userreference']['flgprincipal'], 1);
        $this->assertEquals($ua['Userreference']['flgprincipal'], 0);

        // cambio principal
        $ui = new UserreferenceUI();
        $ui->setPrincipal(null, 'test1@gmail.com', null, 'MioCodTest', 4);

        $uabs = new UserreferenceBS();
        $first = $uabs->unique($principal['Userreference']['id']);
        $uabs = new UserreferenceBS();
        $second = $uabs->unique($idua);
        $this->assertEquals($first['Userreference']['flgprincipal'], 0);
        $this->assertEquals($second['Userreference']['flgprincipal'], 1);

        // resetto principal
        $ui = new UserreferenceUI();
        $ui->setPrincipal(null, 'test1@gmail.com', null, $principal['Userreference']['cod'], 4);

        $uabs = new UserreferenceBS();
        $first = $uabs->unique($principal['Userreference']['id']);
        $uabs = new UserreferenceBS();
        $second = $uabs->unique($idua);
        $this->assertEquals($first['Userreference']['flgprincipal'], 1);
        $this->assertEquals($second['Userreference']['flgprincipal'], 0);

        // delete
        MysqlUtilityTest::deleteLast($dbo, "contactreferences", "val='prova@prova.it'");
        MysqlUtilityTest::deleteLast($dbo, "userreferences", "cod='MioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "contactreferences", $autoIncrementReference);
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userreferences", $autoIncrementUserreference);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "contactreferences", "val='prova@prova.it'");
        MysqlUtilityTest::verifyDeleted($dbo, $this, "userreferences", "cod='MioCodTest'");
    }

    public function testUserattachmentPrincipal() {
        $ui = new UserattachmentUI();
        $principal = $ui->getPrincipal(null, 'test1@gmail.com', 6);
        $this->assertEquals($principal['Userattachment']['flgprincipal'], 1);

        // aggiungo un nuovo contatto
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrementAttachment = MysqlUtilityTest::getAutoIncrement($dbo, "attachments");
        $autoIncrementUserattachment = MysqlUtilityTest::getAutoIncrement($dbo, "userattachments");

        // obj
        $bs = new AttachmentBS();
        $obj = $bs->instance();
        $obj['Attachment']['cod'] = "NUOVO_COD";
        $id = $bs->save($obj);

        $uabs = new UserattachmentBS();
        $ua = $uabs->instance();
        $ua['Userattachment']['user'] = $principal['Userattachment']['user'];
        $ua['Userattachment']['attachment'] = $id;
        $ua['Userattachment']['tpattachment'] = 6;
        $ua['Userattachment']['cod'] = 'MioCodTest';
        $idua = $uabs->save($ua);
        $this->assertEquals($principal['Userattachment']['flgprincipal'], 1);
        $this->assertEquals($ua['Userattachment']['flgprincipal'], 0);

        // cambio principal
        $ui = new UserattachmentUI();
        $ui->setPrincipal(null, 'test1@gmail.com', null, 'MioCodTest', 6);

        $uabs = new UserattachmentBS();
        $first = $uabs->unique($principal['Userattachment']['id']);
        $uabs = new UserattachmentBS();
        $second = $uabs->unique($idua);
        $this->assertEquals($first['Userattachment']['flgprincipal'], 0);
        $this->assertEquals($second['Userattachment']['flgprincipal'], 1);

        // resetto principal
        $ui = new UserattachmentUI();
        $ui->setPrincipal(null, 'test1@gmail.com', null, $principal['Userattachment']['cod'], 6);

        $uabs = new UserattachmentBS();
        $first = $uabs->unique($principal['Userattachment']['id']);
        $uabs = new UserattachmentBS();
        $second = $uabs->unique($idua);
        $this->assertEquals($first['Userattachment']['flgprincipal'], 1);
        $this->assertEquals($second['Userattachment']['flgprincipal'], 0);

        // delete
        MysqlUtilityTest::deleteLast($dbo, "attachments", "cod='NUOVO_COD'");
        MysqlUtilityTest::deleteLast($dbo, "userattachments", "cod='MioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "attachments", $autoIncrementAttachment);
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userattachments", $autoIncrementUserattachment);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "attachments", "cod='NUOVO_COD'");
        MysqlUtilityTest::verifyDeleted($dbo, $this, "userattachments", "cod='MioCodTest'");
    }
}