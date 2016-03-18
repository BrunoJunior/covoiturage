<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services\user;

// BO
use covoiturage\classes\metier\User as BO;
// Helper
use covoiturage\utils\HRequete;
use Exception;

/**
 * Description of Edit
 *
 * @author bruno
 */
class Edit extends \covoiturage\classes\abstraites\Service {
    /**
     * Modification d'un utilisateurs
     * @throws Exception
     */
    public function executerService() {
        $user = new BO(HRequete::getPOST('id'));
        $connectedUser = $this->getUser();
        if (!$connectedUser->admin && !$user->id !== $connectedUser->id) {
            throw new Exception('Vous n\'êtes pas autorisé à modifier cet utilisateur !');
        }
        $user->email = HRequete::getPOSTObligatoire('email');
        $user->prenom = HRequete::getPOSTObligatoire('prenom');
        $user->nom = HRequete::getPOSTObligatoire('nom');
        $user->tel = HRequete::getPOST('tel');
        $user->admin = (HRequete::getPOST('admin') == 'on');

        $newMdp = HRequete::getPOST('password', FALSE);
        if ($newMdp) {
            $oldPass = HRequete::getPOST('old_password');
            $checkPass = HRequete::getPOSTObligatoire('password_check');
            if (!$user->checkPassword($oldPass)) {
                throw new Exception('Ancien mot de passe incorrect !');
            }
            if ($newMdp != $checkPass) {
                throw new Exception('Vous n\'avez pas re-saisi le même mot de passe !');
            }
            $user->setPassword($newMdp);
        }
        $user->merger();
        $this->setMessage('Utilisateur modifié');
    }

}
