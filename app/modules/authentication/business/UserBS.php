<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("User", "Model");

class UserBS extends AppGenericBS {

	function __construct() {
		parent::__construct('User');
	}

	function login($username, $password) {
		$sql= "SELECT * FROM users as User WHERE User.username='$username' AND User.password=SHA1('$password')";
		$object= $this->query($sql);
		return ! empty($object) ? $this->unique($object ['User'] ['id']) : null;
	}
}