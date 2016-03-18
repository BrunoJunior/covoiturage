<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services\usergroup;

// Service traitement
use covoiturage\classes\abstraites\Service;
// BO
use covoiturage\classes\metier\UserGroup as BO;
// Helper
use covoiturage\utils\HRequete;
use Exception;

/**
 * Description of Remove
 *
 * @author bruno
 */
class Remove extends Service {
    /**
     * Détachement d'un utilisateur d'un groupe
     * @throws Exception
     */
    public function executerService() {
        $user = $this->getUser();
        $usergroup = new BO(HRequete::getPOST('id'));
        $group = $usergroup->getGroup();
        if (!$user->admin && !$group->isUserAdminGroup($user)) {
            throw new Exception("Vous n'êtes pas autorisé à effectuer cette action !");
        }
        $usergroup->supprimer();
    }
}
