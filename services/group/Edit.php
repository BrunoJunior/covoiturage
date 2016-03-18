<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services\group;

// Service Traitement
use covoiturage\classes\abstraites\Service;
// BO
use covoiturage\classes\metier\Group as BO;
// Helpers
use covoiturage\utils\HRequete;
use Exception;

/**
 * Description of Remove
 *
 * @author bruno
 */
class Edit extends Service {

    /**
     * Mise à jour d'un groupe
     * @throws Exception
     */
    public function executerService() {
        $group = new BO(HRequete::getPOST('id'));
        $user = $this->getUser();
        if (!$group->isUserAdminGroup($user) && !$user->admin) {
            throw new Exception('Vous n\'êtes pas autorisé à modifier ce groupe !');
        }
        $group->nom = HRequete::getPOST('group_name');
        $group->merger();
        $this->setMessage('Groupe modifié !');
    }

}
