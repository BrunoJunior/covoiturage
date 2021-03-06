<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services;

use covoiturage\classes\abstraites\Service;
use covoiturage\classes\metier\User as UserBO;
use covoiturage\classes\metier\Covoiturage as CovoiturageBO;
use covoiturage\classes\metier\Group as GroupBO;
use covoiturage\classes\metier\Passager as PassagerBO;
use covoiturage\classes\metier\UserGroup as UserGroupBO;
use covoiturage\classes\metier\TrajetPrevisionnel as TrajetPrevisionnelBO;
use covoiturage\classes\metier\PassagerPrevisionnel as PassagerPrevisionnelBO;

/**
 * Description of Install
 *
 * @author bruno
 */
class Install extends Service {

    /**
     * Installation
     */
    public function executerService() {
        UserBO::install();
        GroupBO::install();
        UserGroupBO::install();
        CovoiturageBO::install();
        PassagerBO::install();
        TrajetPrevisionnelBO::install();
        PassagerPrevisionnelBO::install();
    }

}
