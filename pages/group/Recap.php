<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages\group;

// Vue
use covoiturage\classes\abstraites\ServiceVue;
// BP
use covoiturage\classes\presentation\Group as BP;
// BO
use covoiturage\classes\metier\Group as BO;
// Helpers
use covoiturage\utils\HRequete;
use Exception;

/**
 * Description of Liste
 *
 * @author bruno
 */
class Recap extends ServiceVue {

    /**
     * Affichage de la vue de récapitulatif d'un groupe
     * @throws Exception
     */
    public function executerService() {
        $group = new BO(HRequete::getPOST('id'));
        if (!$group->isUserPresent($this->getUser()) && !$this->getUser()->admin) {
            throw new Exception('Vous n\'êtes pas autorisé à visualiser cette page !');
        }
        echo "<div id='cov-group-recap'><div class='row'><div class='col-xs-12'>";
        echo BP::getRecapitulatifHtml($group);
        echo "</div></div></div>";
    }

    public function getTitre() {
        $group = new BO(HRequete::getPOST('id'));
        return $group->nom . ' - Récapitulatif';
    }

}
