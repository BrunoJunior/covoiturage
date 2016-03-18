<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services\user;

// BO
use covoiturage\classes\metier\User as BO;
// Helpers
use covoiturage\utils\HRequete;
use Exception;

/**
 * Description of Login
 *
 * @author bruno
 */
class Login extends \covoiturage\classes\abstraites\Service {

    /**
     * Connexion
     * @throws Exception
     */
    public function executerService() {
        $email = HRequete::getPOST('user_email');
        $password = HRequete::getPOST('user_password');
        if (!BO::connecter($email, $password)) {
            throw new Exception('Identification incorrecte !');
        }
    }
}
