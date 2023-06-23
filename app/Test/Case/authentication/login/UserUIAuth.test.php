<?php
App::uses("UserUI", "modules/authentication/delegate");

class UserUIAuthTest extends CakeTestCase {

	function testLogin() {
		$ui= new UserUI();
		$authtoken= $ui->login('test1@gmail.com', 'Test_001');
		$this->assertEquals($authtoken, CakeSession::read('test1@gmail.com'));
		
		CakeSession::delete('test1@gmail.com');
		
		$this->assertEquals(CakeSession::check('test1@gmail.com'), false);
	}

	function testCheckSession() {
		$ui= new UserUI();
		$authtoken= $ui->login('test1@gmail.com', 'Test_001');
		$this->assertEquals($authtoken, CakeSession::read('test1@gmail.com'));
		
		$result= $ui->checkSession('test1@gmail.com', $authtoken);
		$this->assertEquals($result, true);
		
		CakeSession::delete('test1@gmail.com');
		
		$this->assertEquals(CakeSession::check('test1@gmail.com'), false);
	}

	function testLogout() {
		$ui= new UserUI();
		$authtoken= $ui->login('test1@gmail.com', 'Test_001');
		$this->assertEquals($authtoken, CakeSession::read('test1@gmail.com'));
		
		$result= $ui->checkSession('test1@gmail.com', $authtoken);
		$this->assertEquals($result, true);
		
		$result= $ui->logout('test1@gmail.com');
		$this->assertEquals($result, true);
		
		$this->assertEquals(CakeSession::check('test1@gmail.com'), false);
	}
}