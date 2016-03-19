<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services\user;

// Service de traitement
use covoiturage\classes\abstraites\Service;
// BO
use covoiturage\classes\metier\User as BO;
// Helper
use covoiturage\utils\HRequete;
use Exception;

/**
 * Description of ForgotPwd
 *
 * @author bruno
 */
class ForgotPwd extends Service {

    /**
     * Oublie de mot de passe
     */
    public function executerService() {
        $email = HRequete::getPOSTObligatoire('email');
        $user = BO::chargerParEmail($email);
        if (!$user->existe()) {
            throw new Exception('Adresse e-mail inconnue !');
        }
    }
}
