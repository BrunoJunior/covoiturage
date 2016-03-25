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
 * Description of Inscription
 *
 * @author bruno
 */
class Inscription extends \covoiturage\classes\abstraites\ServiceVue {

    /**
     * Vue formulaire connexion
     */
    public function executerService() {
        echo BP::getInscriptionForm();
    }

    public function getTitre() {
        return 'Inscription';
    }

    public function isSecurised() {
        return FALSE;
    }
    
    protected function isFormValidation() {
        return TRUE;
    }

}
