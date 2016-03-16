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

    public function getProchainConducteurPropose() {
        $userGroups = $this->getListeUserGroup();
        $conducteur = NULL;
        $creditMin = 0;
        foreach ($userGroups as $userGroup) {
            $user = $userGroup->getUser();
            $credit = $user->getSommeCreditsTrajet($this);
            if ($conducteur === NULL || $credit < $creditMin) {
                $conducteur = $user;
                $creditMin = $credit;
            }
        }
        return $conducteur;
    }

    public function getRecapitulatif() {
        $recap = parent::getRecapitulatif();
        $recapFinal = [];
        foreach ($recap as $row) {
            $recapFinal[$row['conducteur_id']][$row['user_id']] = $row['nb'];
        }
        return $recapFinal;
    }

}
