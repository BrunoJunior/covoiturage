<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services\user;

/**
 * Description of Logout
 *
 * @author bruno
 */
class Logout extends \covoiturage\classes\abstraites\Service {
    public function executerService() {
        // On détruit les variables de notre session
        session_unset ();
        // On détruit notre session
        session_destroy ();
    }
}
