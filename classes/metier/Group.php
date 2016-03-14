<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\metier;

use covoiturage\classes\dao\Group as GroupDAO;
use covoiturage\classes\metier\User;

/**
 * Description of Group
 *
 * @author bruno
 */
class Group extends GroupDAO {

    public function isUserPresent(User $user) {
        $userGroup = $this->getUserGroup($user);
        return $userGroup->existe();
    }

    public function isUserAdminGroup(User $user) {
        $userGroup = $this->getUserGroup($user);
        return $userGroup->group_admin;
    }

}
