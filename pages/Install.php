<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages;

use covoiturage\classes\abstraites\Service;
use covoiturage\classes\metier\User as UserBO;
use covoiturage\classes\metier\Covoiturage as CovoiturageBO;
use covoiturage\classes\metier\Group as GroupBO;
use covoiturage\classes\metier\Passager as PassagerBO;
use covoiturage\classes\metier\UserGroup as UserGroupBO;

/**
 * Description of Install
 *
 * @author bruno
 */
class Install extends Service {

    public function executerService() {
        UserBO::install();
        CovoiturageBO::install();
        GroupBO::install();
        PassagerBO::install();
        UserGroupBO::install();

        echo 'Installation terminée !';
    }

    public function getTitre() {
        return 'Installation';
    }

}
