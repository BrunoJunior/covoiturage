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
// Service vue
use covoiturage\pages\user\NewPassword;
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
        $isOk = $user->contacter('Oublie de mot de passe',
            '<p>Vous avez oublié votre mot de passe !</p>
            <p>Pas de panique, cliquez sur le lien ci-dessous pour en renseigner un nouveau !</p>
            <p><a href="'.NewPassword::getUrl($user->id, ['token'=>$user->getNewToken()]).'">Obtenir un nouveau mot de passe</a></p>');
        if (!$isOk) {
            throw new Exception('Erreur lors de l\'envoi du message !');
        }
        $this->setMessage('Un email pour réinitialiser votre mot de passe vous a été envoyé !');
    }

    /**
     * Le service n'est pas sécurisé
     * @return boolean
     */
    public function isSecurised() {
        return FALSE;
    }
}
