<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages\covoiturage;

use covoiturage\classes\abstraites\ServiceVue;
use covoiturage\classes\metier\Group as GroupBO;
use covoiturage\classes\presentation\Covoiturage as CovoiturageBP;
use covoiturage\utils\HRequete;
use Exception;

/**
 * Description of Liste
 *
 * @author bruno
 */
class Liste extends ServiceVue {

    public function executerService() {
        $group = new GroupBO(HRequete::getPOST('group_id'));
        $user = $this->getUser();
        echo CovoiturageBP::getHtmlTableCond($group, $user);
    }

    public function getTitre() {
        return 'Liste des trajets';
    }

    public function isComplete() {
        return FALSE;
    }

}
