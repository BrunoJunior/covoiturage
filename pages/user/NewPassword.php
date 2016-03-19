<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages\user;
use covoiturage\classes\abstraites\ServiceVue;
// BO
use covoiturage\classes\metier\User as BO;
// BP
use covoiturage\classes\presentation\User as BP;
// Helpers
use covoiturage\utils\HRequete;

/**
 * Description of NewPassword
 *
 * @author bruno
 */
class NewPassword extends ServiceVue {

    /**
     * Vue
     */
    public function executerService() {
        $user = new BO(HRequete::getPOSTObligatoire('id'));
        $token = HRequete::getPOSTObligatoire('token');
        $user->checkToken($token);
        echo BP::getNewPasswordForm($user);
    }

    /**
     * Titre de page
     * @return string
     */
    public function getTitre() {
        return 'Oublie de mot de passe';
    }

    /**
     * Le service n'est pas sécurisé
     * @return boolean
     */
    public function isSecurised() {
        return FALSE;
    }
}
