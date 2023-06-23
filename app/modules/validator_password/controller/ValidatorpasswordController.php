<?php
App::uses('AppController', 'Controller');
App::uses("PasswordUI", "modules/validator_password/delegate");

class ValidatorpasswordController extends AppController {

    public function home() {
        $ui = new PasswordUI();
        $passwordValid = "Prova123#!";
        $passwordNotValid = "pro";
        $validL1 = $ui->validate($passwordValid, 5, 10, 1);
        $validL2 = $ui->validate($passwordValid, 5, 10, 2);
        $validL3 = $ui->validate($passwordValid, 5, 10, 3);
        $validL4 = $ui->validate($passwordValid, 5, 10, 4);
        $notValidL1 = $ui->validate($passwordNotValid, 5, 10, 1);
        $notValidL2 = $ui->validate($passwordNotValid, 5, 10, 2);
        $notValidL3 = $ui->validate($passwordNotValid, 5, 10, 3);
        $notValidL4 = $ui->validate($passwordNotValid, 5, 10, 4);
        $this->set("passwordValid", $passwordValid);
        $this->set("passwordNotValid", $passwordNotValid);
        $this->set("level1", array("valid" => $validL1, "notValid" => $notValidL1));
        $this->set("level2", array("valid" => $validL2, "notValid" => $notValidL2));
        $this->set("level3", array("valid" => $validL3, "notValid" => $notValidL3));
        $this->set("level4", array("valid" => $validL4, "notValid" => $notValidL4));
    }

}