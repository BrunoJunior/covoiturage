<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services\group;

use covoiturage\classes\abstraites\Service;
use covoiturage\classes\metier\Group as GroupBO;
use covoiturage\utils\HRequete;
use Exception;

/**
 * Description of Remove
 *
 * @author bruno
 */
class Remove extends Service {
    public function executerService() {
        $group = new GroupBO(HRequete::getPOST('id'));
        $user = $this->getUser();
        if (!$user->admin && !$group->isUserAdminGroup($user)) {
            throw new Exception("Vous n'êtes pas autorisé à effectuer cette action !");
        }
        $group->supprimer();
    }
}
