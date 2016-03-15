<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services\usergroup;

use covoiturage\classes\abstraites\Service;
use covoiturage\classes\metier\UserGroup as UserGroupBO;
use covoiturage\utils\HRequete;
use Exception;

/**
 * Description of Remove
 *
 * @author bruno
 */
class Remove extends Service {
    public function executerService() {
        $user = $this->getUser();
        $usergroup = new UserGroupBO(HRequete::getPOST('id'));
        $group = $usergroup->getGroup();
        if (!$user->admin && !$group->isUserAdminGroup($user)) {
            throw new Exception("Vous n'êtes pas autorisé à effectuer cette action !");
        }
        $usergroup->supprimer();
    }
}
