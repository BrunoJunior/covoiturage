<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages\user;

use covoiturage\classes\presentation\User as UserBP;

/**
 * Description of Login
 *
 * @author bruno
 */
class Login extends \covoiturage\classes\abstraites\ServiceVue {

    public function executerService() {

        echo UserBP::getConnexionForm();
    }

    public function getTitre() {
        return 'Connexion';
    }

    public function isSecurised() {
        return FALSE;
    }

}
