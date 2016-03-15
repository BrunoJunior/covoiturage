<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services\group;

use covoiturage\classes\abstraites\Service;
use covoiturage\utils\HRequete;
use covoiturage\classes\presentation\Group as GroupBP;
use Exception;

/**
 * Description of Remove
 *
 * @author bruno
 */
class Edit extends Service {

    public function executerService() {
        $id = HRequete::getPOST('id');
        $group = new GroupBP($id);
        $user = $this->getUser();
        if (!$group->isUserAdminGroup($user) && !$user->admin) {
            throw new Exception('Vous n\'êtes pas autorisé à modifier ce groupe !');
        }
        $group->nom = HRequete::getPOST('group_name');
        $group->merger();
        $this->setMessage('Groupe modifié !');
    }

}
