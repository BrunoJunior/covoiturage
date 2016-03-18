<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages\user;

// BP
use covoiturage\classes\presentation\User as BP;

/**
 * Description of Login
 *
 * @author bruno
 */
class Login extends \covoiturage\classes\abstraites\ServiceVue {

    /**
     * Vue formulaire connexion
     */
    public function executerService() {
        echo BP::getConnexionForm();
    }

    public function getTitre() {
        return 'Connexion';
    }

    public function isSecurised() {
        return FALSE;
    }

}
