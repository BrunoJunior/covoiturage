<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages\user;

use covoiturage\classes\presentation\User as UserBP;
use covoiturage\utils\HRequete;

/**
 * Description of Login
 *
 * @author bruno
 */
class Login extends \covoiturage\classes\abstraites\ServiceVue {
    public function executerService() {
        $this->traiterSubmit();
        echo UserBP::getConnexionForm();
    }

    public function getTitre() {
        return 'Connexion';
    }

    /**
     * Traitement du formulaire
     */
    private function traiterSubmit() {
        if (HRequete::isParamPostPresent('submit')) {
            $email = HRequete::getPOST('user_email');
            $password = HRequete::getPOST('user_password');
            if (UserBP::connecter($email, $password)) {
                header ('location: /index.php');
            } else {
                throw new Exception('Identification incorrecte !');
            }
        }
    }

    public function isSecurised() {
        return HRequete::isParamPostPresent('submit');
    }
}
