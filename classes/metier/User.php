<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\metier;

use covoiturage\classes\dao\User as UserDAO;

/**
 * Description of User
 *
 * @author bruno
 */
class User extends UserDAO {

    public function getNbVoyageConducteur() {
        return count($this->getListeCovoiturage());
    }
    
    public function checkPassword($password) {
        return password_verify($password, $this->password);
    }

    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
}
