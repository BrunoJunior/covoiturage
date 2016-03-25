<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\metier;

// DAO
use covoiturage\classes\dao\UserGroup as DAO;

use Exception;

/**
 * Description of UserGroup
 *
 * @author bruno
 */
class UserGroup extends DAO {
    protected function avantAjout() {
        $group = $this->getGroup();
        if ($group->isUserPresent($this->getUser())) {
            throw new Exception("L'utilisateur est déjà présent !");
        }
    }
}
