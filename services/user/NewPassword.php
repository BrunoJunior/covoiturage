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
class NewPassword extends Service {

    /**
     * Oublie de mot de passe
     */
    public function executerService() {
        $user = new BO(HRequete::getPOSTObligatoire('id'));
        $token = HRequete::getPOSTObligatoire('token');
        $user->checkToken($token);
        $password = HRequete::getPOSTObligatoire('password');
        $password2 = HRequete::getPOSTObligatoire('password2');
        if ($password !== $password2) {
            throw new Exception("Veuillez saisir deux fois le même mot de passe !");
        }
        $user->setPassword($password);
        $user->merger();
        $this->setMessage('Votre mot de passe a bien été modifié !');
    }

    /**
     * Le service n'est pas sécurisé
     * @return boolean
     */
    public function isSecurised() {
        return FALSE;
    }
}
