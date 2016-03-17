<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\metier;

use covoiturage\classes\dao\User as UserDAO;
use covoiturage\classes\metier\Group as GroupBO;

/**
 * Description of User
 *
 * @author bruno
 */
class User extends UserDAO {

    /**
     * Nb de fois conducteur
     * @param Group $group
     * @return int
     */
    public function getNbVoyageConducteur($group = NULL) {
        return $this->getListeCovoiturage($group, NULL, 0, 1, static::MODE_COUNT, FALSE);
    }
    
    public function checkPassword($password) {
        if (empty($this->password) && empty($password)) {
            return TRUE;
        }
        return password_verify($password, $this->password);
    }

    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function toHtml() {
        return $this->prenom . ' ' . $this->nom;
    }

    public function isDansGroupe(GroupBO $group) {
        return $group->isUserPresent($this);
    }
    
    public function getNbConducteurDe(GroupBO $group, User $user) {
        return $this->getListeCovoiturage($group, $user, 0, 1, static::MODE_COUNT);
    }
    
    public function getNbPassagerDe(GroupBO $group, User $user) {
        return $this->getListeCovoituragePassager($group, $user, 0, 1, static::MODE_COUNT);
    }

    public function getSommeCreditsTrajet(GroupBO $group) {
        return $this->getCreditsConducteur($group->id) - $this->getCreditsPassager($group->id);
    }
}
