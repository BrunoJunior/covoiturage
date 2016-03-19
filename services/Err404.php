<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages;

// Vue
use covoiturage\classes\abstraites\Service;

/**
 * Description of 404
 *
 * @author bruno
 */
class Err404 extends Service {

    /**
     * Page 404
     */
    public function executerService() {
        throw new Exception('Service introuvable !');
    }

    /**
     * La page n'est pas sécurisé
     * @return boolean
     */
    public function isSecurised() {
        return FALSE;
    }
}
