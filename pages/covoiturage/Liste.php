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
        $numPage = HRequete::getPOST('num_page', 1);
        $type = HRequete::getPOST('type', 'conducteur');
        $max = HRequete::getPOST('max', 0);
        $user = $this->getUser();
        if ($type === 'conducteur') {
            echo CovoiturageBP::getHtmlTableCond($group, $user, $max, $numPage);
        } elseif ($type === 'passager') {
            echo CovoiturageBP::getHtmlTablePass($group, $user, $max, $numPage);
        }
    }

    public function getTitre() {
        return 'Liste des trajets';
    }

    public function isComplete() {
        return FALSE;
    }

}
