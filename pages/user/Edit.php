<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages\user;

use covoiturage\classes\abstraites\ServiceVue;
use covoiturage\utils\HRequete;
use covoiturage\classes\presentation\User as UserBP;
use Exception;

/**
 * Description of Add
 *
 * @author bruno
 */
class Edit extends ServiceVue {

    /**
     * Utilisateur lié
     * @var UserBP
     */
    private $user;

    /**
     * Execution du service
     */
    public function executerService() {
        $id = HRequete::getPOST('id');
        $this->user = new UserBP($id);
        $user = $this->getUser();
        if (!$user->admin && $this->user->id !== $user->id) {
            throw new Exception('Vous n\'êtes pas autorisé à modifier cet utilisateur !');
        }
        echo $this->user->getForm();
    }

    /**
     * Titre
     * @return string
     */
    public function getTitre() {
        return 'Gestion des utilisateurs';
    }

}
