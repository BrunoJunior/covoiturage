<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages\group;

// Vue
use covoiturage\classes\abstraites\ServiceVue;
// BP
use covoiturage\classes\presentation\Group as BP;
// BO
use covoiturage\classes\metier\Group as BO;
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
        $group = new BO(HRequete::getPOST('id'));
        $user = $this->getUser();
        if (!$group->isUserAdminGroup($user) && !$user->admin) {
            throw new Exception('Vous n\'êtes pas autorisé à modifier ce groupe !');
        }
        echo BP::getForm($group);
    }

    /**
     * Titre
     * @return string
     */
    public function getTitre() {
        return 'Création d\'un nouveau groupe';
    }
}
