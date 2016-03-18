<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages\user;

// Vue
use covoiturage\classes\abstraites\ServiceVue;
// BP
use covoiturage\classes\presentation\User as BP;
// BO
use covoiturage\classes\metier\User as BO;
// Helpers
use covoiturage\utils\HRequete;
use Exception;

/**
 * Description of Add
 *
 * @author bruno
 */
class Edit extends ServiceVue {

    /**
     * Execution du service
     */
    public function executerService() {
        $id = HRequete::getPOST('id');
        $user = new BO($id);
        $connectedUser = $this->getUser();
        if (!$connectedUser->admin && $user->id !== $connectedUser->id) {
            throw new Exception('Vous n\'êtes pas autorisé à modifier cet utilisateur !');
        }
        echo BP::getForm($user);
    }

    /**
     * Titre
     * @return string
     */
    public function getTitre() {
        return 'Gestion des utilisateurs';
    }

}
