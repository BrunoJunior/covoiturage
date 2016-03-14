<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\utils;

use covoiturage\classes\metier\User as UserBO;

/**
 * Description of HSession
 *
 * @author bruno
 */
class HSession {
    public static function getUser() {
        return new UserBO($_SESSION['user_id']);
    }

    public static function setUser(UserBO $user) {
        $_SESSION['user_id'] = $user->id;
    }

}
