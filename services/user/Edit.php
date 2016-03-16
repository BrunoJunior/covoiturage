<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services\user;

use covoiturage\utils\HRequete;
use covoiturage\classes\presentation\User as UserBP;
use Exception;

/**
 * Description of Edit
 *
 * @author bruno
 */
class Edit extends \covoiturage\classes\abstraites\Service {

    private $user;
    
    public function executerService() {
        $id = HRequete::getPOST('id');
        $this->user = new UserBP($id);
        $user = $this->getUser();
        if (!$user->admin && !$this->user->id !== $user->id) {
            throw new Exception('Vous n\'êtes pas autorisé à modifier cet utilisateur !');
        }
        $this->user->email = HRequete::getPOSTObligatoire('email');
        $this->user->prenom = HRequete::getPOSTObligatoire('prenom');
        $this->user->nom = HRequete::getPOSTObligatoire('nom');
        $this->user->tel = HRequete::getPOST('tel');
        $this->user->admin = (HRequete::getPOST('admin') == 'on');

        $newMdp = HRequete::getPOST('password', FALSE);
        if ($newMdp) {
            $oldPass = HRequete::getPOST('old_password');
            $checkPass = HRequete::getPOSTObligatoire('password_check');
            if (!$this->user->checkPassword($oldPass)) {
                throw new Exception('Ancien mot de passe incorrect !');
            }
            if ($newMdp != $checkPass) {
                throw new Exception('Vous n\'avez pas re-saisi le même mot de passe !');
            }
            $this->user->setPassword($newMdp);
        }
        $this->user->merger();
        $this->setMessage('Utilisateur modifié');
    }

}