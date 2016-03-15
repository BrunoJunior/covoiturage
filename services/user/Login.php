<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services\user;
use covoiturage\utils\HRequete;
use covoiturage\classes\presentation\User as UserBP;
use Exception;

/**
 * Description of Login
 *
 * @author bruno
 */
class Login extends \covoiturage\classes\abstraites\Service {

    public function executerService() {
        $email = HRequete::getPOST('user_email');
        $password = HRequete::getPOST('user_password');
        if (!UserBP::connecter($email, $password)) {
            throw new Exception('Identification incorrecte !');
        }
    }
}
